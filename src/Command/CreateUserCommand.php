<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    private UserPasswordEncoderInterface $passwordEncoder;

    private UserRepository $userRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository, EntityManagerInterface $entityManager, string $name = null)
    {
        parent::__construct($name);

        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('fullName', InputArgument::REQUIRED, 'User\'s Full Name')
            ->addArgument('username', InputArgument::REQUIRED, 'Username  (must be unique)')
            ->addArgument('plainPassword', InputArgument::REQUIRED, 'Plain password')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'Whether or not this user has privileged access')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $fullName = (string)$input->getArgument('fullName');
        $username = (string)$input->getArgument('username');
        $plainPassword = (string)$input->getArgument('plainPassword');
        $admin = (bool)$input->getOption('admin');

        $user = $this->userRepository->findOneByUsername($username);

        if ($user instanceof User) {
            throw new \InvalidArgumentException(sprintf('There already exists a user identified by username: `%s`', $username));
        }

        $user = (new User())
            ->setUsername($username)
            ->setName($fullName)
            ->setIsAdmin($admin);

        $password = $this->passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($password);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(sprintf('Created User `%s` identified by `%s`.', $user->getName(), $user->getUsername()));

        return Command::SUCCESS;
    }
}
