<?php

namespace App\Command;

use App\Service\User\UserManagerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateAdminUserCommand extends Command
{
    protected static $defaultName = 'app:create-admin';
    protected UserManagerService $userManager;

    public function __construct(UserManagerService $userManager)
    {
        parent::__construct();
        $this->userManager = $userManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the admin user.')
            ->addArgument('password', InputArgument::REQUIRED, 'The plain password of the admin user.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Admin User Creator',
            '============',
            '',
        ]);

        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $user = $this->userManager->createAdminUser($email, $password);

        $output->writeln([
            'Admin user created successfully!',
            '',
            'Email: ' . $user->getEmail(),
        ]);

        return Command::SUCCESS;
    }
}
