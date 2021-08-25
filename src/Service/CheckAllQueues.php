<?php declare(strict_types=1);

namespace App\Service;

use Bit9\SupervisorControllerBundle\Service\Queue\Configuration;
use Bit9\SupervisorControllerBundle\Service\Queue\Watchdog;

class CheckAllQueues
{
    private Configuration $configuration;
    private Watchdog $watchdog;

    private array $monitors = [];

    public function __construct(Configuration $configuration, Watchdog $watchdog)
    {
        $this->configuration = $configuration;
        $this->watchdog = $watchdog;
    }

    public function execute() : array
    {
        $queues = $this->configuration->execute(null);
        if (empty($queues)) {
            return [];
        }

        $result = [];
        foreach($queues as $queue) {
            $result[$queue['name']] = $this->watchdog->execute($queue['name']);
        }

        return $result;
    }
}