<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Commande pour créer l'administrateur unique du site.
 * 
 * Cette commande permet de créer un administrateur et de le stocker dans la base de données.
 * 
 * @AsCommand(
 *     name="app:create-admin",
 *     description="Créer un administrateur et le stocker dans la base de données"
 * )
 */
#[AsCommand(
    name: 'app:create-admin',
    description: 'Create admin and store in database',
)]
class CreateAdminCommand extends Command
{
    private SymfonyStyle $io;
    private UserPasswordHasherInterface $userPasswordHasher;
    private UserRepository $usersRepository;

    public function __construct(UserRepository $usersRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
        parent::__construct();
        $this->userPasswordHasher = $userPasswordHasher;
        $this->usersRepository = $usersRepository;
    }

    protected function configure(): void
    {   
        $this
            ->addArgument('email', InputArgument::OPTIONAL, 'Email')
            ->addArgument('password', InputArgument::OPTIONAL, 'Password')
            ->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Last name')
            ->addOption('phone', null, InputOption::VALUE_OPTIONAL, 'Phone number')
            ->addOption('address', null, InputOption::VALUE_OPTIONAL, 'Address');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (null === $input->getArgument('email') || null === $input->getArgument('password')) {
            $this->io->title('Création d\'un administrateur');
            $this->io->text('Bonjour, entrez les informations:');
            $this->askArgument($input, 'email', 'Quel est votre email ?');
            $this->askArgument($input, 'password', 'Quel est votre mot de passe ?');
            $this->askOption($input, 'name', 'Quel est votre nom et prénom?');
            $this->askOption($input, 'phone', 'Quel est votre numéro de téléphone ?');
            $this->askOption($input, 'address', 'Quel est votre adresse ?');
        }
    }

    private function askArgument(InputInterface $input, string $argumentName, string $question): void
    {
        $value = $input->getArgument($argumentName);
        if (null === $value) {
            $value = $this->io->ask($question);
            $input->setArgument($argumentName, $value);
        }
    }

    private function askOption(InputInterface $input, string $optionName, string $question): void
    {
        $value = $input->getOption($optionName);
        if (null === $value) {
            $value = $this->io->ask($question);
            $input->setOption($optionName, $value);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {   
        $user = new User();
        $user->setEmail($input->getArgument('email'));
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $input->getArgument('password')
            )
        );
        $user->setName($input->getOption('name'));
        $user->setPhone($input->getOption('phone'));
        $user->setAddress($input->getOption('address'));
        $user->setRoles(['ROLE_ADMIN']);

        $this->usersRepository->save($user, true);

        $this->io->success('Bienvenue sur ton compte personnel.');

        return Command::SUCCESS;
    }
}
