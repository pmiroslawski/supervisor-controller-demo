<?php declare(strict_types=1);

namespace App\Command;

use App\Service\CheckAllQueues;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\Table;

class QueuesWatchdogCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'supervisor:queues:watchdog';

    protected function configure(): void
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Command checks queue and passed readed number of elements into queue conductor.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows to run/stop (or do nothing) some number of processes depends on passed number of queue elements and defined thresholds.')
        ;
    }

    private CheckAllQueues $service;

    public function __construct(string $name = null, CheckAllQueues $service)
    {
        parent::__construct($name);

        $this->service = $service;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $results = $this->service->execute();

        $rows = [];
        foreach($results as $name => $num) {
            $rows[] = [$name, $num];
        }

        $table = new Table($output);
            $table
            ->setHeaders(['Queue name', 'Consumers num'])
            ->setRows($rows)
        ;

        $table->render();

        return Command::SUCCESS;
    }
}