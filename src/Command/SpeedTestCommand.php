<?php

namespace App\Command;

use App\Entity\Server;
use App\Entity\ServiceProvider;
use App\Entity\SpeedTest;
use App\Repository\ServerRepository;
use App\Repository\ServiceProviderRepository;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SpeedTestCommand extends Command
{
    protected static $defaultName = 'app:speed-test';

    private EntityManagerInterface $entityManager;

    private ServerRepository $serverRepository;

    private ServiceProviderRepository $serviceProviderRepository;

    public function __construct(EntityManagerInterface $entityManager, ServerRepository $serverRepository, ServiceProviderRepository $serviceProviderRepository, string $name = null)
    {
        parent::__construct($name);

        $this->entityManager = $entityManager;
        $this->serverRepository = $serverRepository;
        $this->serviceProviderRepository = $serviceProviderRepository;
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

            $process = new Process(['speedtest', '--json']);
            $process->mustRun();

            $data = json_decode($process->getOutput(), true);

            if (empty($data)) {
                throw new \RuntimeException(sprintf('Unexpected response obtained from server: %s', $process->getOutput()));
            }

            $table = new Table($output);

            $table->setHeaderTitle('SPEEDTEST CLI');

            $table->setHeaders([
                'Timestamp', 'ISP', 'IP', 'Server', 'Sponsor', 'Bytes Sent', 'Download',
                'Upload', 'Bytes Received', 'Ping', 'Share',
            ]);

            $table->addRow([
                $data['timestamp'],
                $data['client']['isp'],
                $data['client']['ip'],
                $data['server']['name'],
                $data['server']['sponsor'],
                $data['bytes_sent'],
                $data['download'],
                $data['upload'],
                $data['bytes_received'],
                $data['ping'],
                $data['share'],
            ]);

            $table->render();

            if (!$save) {
                return Command::SUCCESS;
            }

            // get server
            $server = $this->serverRepository->findServerByServerId($data['server']['id']);

            if (!$server instanceof Server) {
                $server = (new Server())
                    ->setServerName($data['server']['name'])
                    ->setSponsor($data['server']['sponsor'])
                    ->setServerId($data['server']['id']);

                $this->entityManager->persist($server);
            }

            // get isp
            $serviceProvider = $this->serviceProviderRepository->findOneByIp($data['client']['ip']);

            if (!$serviceProvider instanceof ServiceProvider) {
                $serviceProvider = (new ServiceProvider())
                    ->setIpAddress($data['client']['ip'])
                    ->setCountry($data['client']['country'])
                    ->setIsp($data['client']['isp'])
                    ->setIspRating($data['client']['isprating'])
                    ->setIspulavg($data['client']['ispulavg'])
                    ->setLatitude($data['client']['lat'])
                    ->setLoggedin($data['client']['loggedin'])
                    ->setLongitude($data['client']['lon'])
                    ->setRating($data['client']['rating'])
                ;

                $this->entityManager->persist($serviceProvider);
            }

            // add speedtest record
            $speedTest = (new SpeedTest())
                ->setServer($server)
                ->setServiceProvider($serviceProvider)
                ->setShare((string)$data['share'])
                ->setPing($data['ping'])
                ->setTimestamp(Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $data['timestamp'])->toDateTimeImmutable())
                ->setBytesReceived($data['bytes_received'])
                ->setBytesSent($data['bytes_sent'])
                ->setDownload($data['download'])
                ->setUpload($data['upload'])
            ;

            $this->entityManager->persist($speedTest);
            $this->entityManager->flush();

            return Command::SUCCESS;
        } catch (ProcessFailedException $exception) {
            $io->error($exception->getMessage());
        }

        return Command::FAILURE;
    }
}
