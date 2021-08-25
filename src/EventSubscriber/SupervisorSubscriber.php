<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Bit9\SupervisorControllerBundle\Event\ProcessStartedEvent;
use Bit9\SupervisorControllerBundle\Event\ProcessStoppedEvent;
use Bit9\SupervisorControllerBundle\Event\ProcessesStartedEvent;
use Bit9\SupervisorControllerBundle\Event\ProcessesStoppedEvent;

class SupervisorSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ProcessStartedEvent::class => 'onProcessStarted',
            ProcessStoppedEvent::class => 'onProcessStopped',
            ProcessesStartedEvent::class => 'onProcessesStarted',
            ProcessesStoppedEvent::class => 'onProcessesStopped',
        ];
    }

    public function onProcessStarted(ProcessStartedEvent $event)
    {
//         dump($event);
    }

    public function onProcessStopped(ProcessStoppedEvent $event)
    {
//         dump($event);
    }

    public function onProcessesStarted(ProcessesStartedEvent $event)
    {
//         dump($event);
    }

    public function onProcessesStopped(ProcessesStoppedEvent $event)
    {
//         dump($event);
    }
}