<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Create an admin user for testing',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Create admin user
        $admin = new User();
        $admin->setEmail('admin@esmer.shop');
        $admin->setFirstName('Admin');
        $admin->setLastName('Esmer');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setVerified(true);

        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin123');
        $admin->setPassword($hashedPassword);

        $this->entityManager->persist($admin);

        // Create regular user
        $user = new User();
        $user->setEmail('user@esmer.shop');
        $user->setFirstName('Jean');
        $user->setLastName('Dupont');
        $user->setRoles(['ROLE_USER']);
        $user->setVerified(true);

        $hashedPassword = $this->passwordHasher->hashPassword($user, 'user123');
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('Users created successfully!');
        $io->table(
            ['Email', 'Password', 'Role'],
            [
                ['admin@esmer.shop', 'admin123', 'ROLE_ADMIN'],
                ['user@esmer.shop', 'user123', 'ROLE_USER'],
            ]
        );

        return Command::SUCCESS;
    }
}
