<?php

namespace App\Command;

use App\Entity\Server;
use App\Entity\SpeedTest;
use App\Repository\ServerRepository;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\String\AbstractString;
use function Symfony\Component\String\u;

class SpeedTestCommand extends Command
{
    protected static $defaultName = 'app:speed-test';

    private EntityManagerInterface $entityManager;

    private ServerRepository $serverRepository;

    public function __construct(EntityManagerInterface $entityManager, ServerRepository $serverRepository, string $name = null)
    {
        parent::__construct($name);

        $this->entityManager = $entityManager;
        $this->serverRepository = $serverRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addOption('save', 'N', InputOption::VALUE_NONE, 'Save the results to the database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $save = (bool)$input->getOption('save');

        try {
            // check if the speedtest binary is installed
            $process = new Process(['speedtest', '--version']);
            $process->mustRun();

            $io->text($process->getOutput());

            $process->clearOutput();

            $process = new Process(['speedtest', '--csv-header']);
            $process->mustRun();

            $header = array_map(
                fn(AbstractString $s) => $s->trim()->toString(),
                u($process->getOutput())->trim()->split(',')
            );

            $process = new Process(['speedtest', '--csv']);
            $process->mustRun();

            $table = (new Table($output))
                ->setHeaders($header); // we will display the results to the console in table format.

            $reader = Reader::createFromString($process->getOutput());

            foreach ($reader->getRecords($header) as $record) {
                $table->addRow(array_values($record));

                if (!$save) {
                    continue;
                }

                if (empty($record['Server ID'] ?? 0)) {
                    $io->warning('`Server ID` field is missing from result => ' . json_encode($record));
                    continue;
                }

                $server = $this->serverRepository->findServerByServerId($record['Server ID']);

                if (!$server instanceof Server) {
                    $server = (new Server())
                        ->setServerId(intval($record['Server ID']))
                        ->setSponsor($record['Sponsor'])
                        ->setServerName($record['Server Name']);

                    $this->entityManager->persist($server);
                }

                $speedTest = (new SpeedTest())
                    ->setServer($server)
                    // 2020-12-02T11:19:12.834113Z
                    ->setTimestamp(Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $record['Timestamp'])->toDateTimeImmutable())
                    ->setDistance($record['Distance'])
                    ->setPing($record['Ping'])
                    ->setDownloadSpeed($record['Download'])
                    ->setUploadSpeed($record['Upload'])
                    ->setShare($record['Share'])
                    ->setIpAddress($record['IP Address']);

                $this->entityManager->persist($speedTest);

                $this->entityManager->flush();
            }

            $table->render();

            return Command::SUCCESS;
        } catch (ProcessFailedException $exception) {
            $io->error($exception->getMessage());
        }

        return Command::FAILURE;
    }
}
