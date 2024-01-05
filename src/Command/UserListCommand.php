<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\User;

#[AsCommand(
    name: 'app:user-list',
    description: 'List all available users',
)]
class UserListCommand extends Command
{
    public function __construct(private UserRepository $userRepo)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepo->findAll();

        $table = new Table($output);
        $table->setHeaders(['ID', 'Username',  'Role']);
        $table->setRows(array_map(fn (User $user) => [
            $user->getId(), $user->getUsername(), implode(', ', $user->getRoles())
        ], $users));

        $table->render();

        $users = $this->userRepo->findAll();

        return Command::SUCCESS;
    }
}
