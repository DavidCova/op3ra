<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:user-delete',
    description: 'Delete a user by its ID',
)]
class UserDeleteCommand extends Command
{

    public function __construct(private UserRepository $userRepo)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'ID of the user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $io = new SymfonyStyle($input, $output);

        $user_id = $input->getArgument('id');
        $user = $this->userRepo->findOneById($user_id);

        if (!$user)
        {
            $io->error("User with ID $user_id not found!");
            return Command::FAILURE;
        }

        $this->userRepo->remove($user, TRUE);

        $io->success('User with ID ' . $user_id . ' deleted successfully!');
        $io->info(var_export($user, TRUE));

        return Command::SUCCESS;
    }

}
