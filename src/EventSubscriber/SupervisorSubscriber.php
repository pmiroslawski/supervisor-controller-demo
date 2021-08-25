<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Bit9\SupervisorControllerBundle\Event\ProcessStartedEvent;
use Bit9\SupervisorControllerBundle\Event\ProcessStoppedEvent;
use Bit9\SupervisorControllerBundle\Event\ProcessesStartedEvent;
use Bit9\SupervisorControllerBundle\Event\ProcessesStoppedEvent;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\InlineKeyboardMarkup;
use Symfony\Component\Notifier\Bridge\Telegram\TelegramOptions;

class SupervisorSubscriber implements EventSubscriberInterface
{
    private ChatterInterface $chatter;

    public function __construct(ChatterInterface $chatter)
    {
        $this->chatter = $chatter;
    }

    private function notify(string $text)
    {
        $telegramOptions = (new TelegramOptions())
            ->parseMode(TelegramOptions::PARSE_MODE_HTML)
            ->disableWebPagePreview(true)
            ->replyMarkup((new InlineKeyboardMarkup()))
        ;

        $message = (new ChatMessage($text, $telegramOptions))
            ->transport('telegram')
        ;

        try {
            return $this->chatter->send($message);
        }
        catch (\Exception $e) {
            // @todo: log error
        }
    }

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
        /*
        $text = "[Supervisor Watchdog Service] ";
        $text .= sprintf("started process %s", $event->getProcessName());

        $this->notify($text);
        */
    }

    public function onProcessStopped(ProcessStoppedEvent $event)
    {
        /*
        $text = "[Supervisor Watchdog Service] ";
        $text .= sprintf("stopped process %s", $event->getProcessName());

        $this->notify($text);
        */
    }

    public function onProcessesStarted(ProcessesStartedEvent $event)
    {
        $text = "[Supervisor Watchdog Service] ";
        $text .= sprintf("Increased number of consumers for group %s \nCurrently %d %s running.", $event->getProgramName(), $event->getProcessesNum(), $event->getProcessesNum() == 1 ? 'consumer is' : 'consumers are');

        $this->notify($text);
    }

    public function onProcessesStopped(ProcessesStoppedEvent $event)
    {
        $text = "[Supervisor Watchdog Service] ";
        $text .= sprintf("Decreased number of consumers for group %s \nCurrently %d %s running.", $event->getProgramName(), $event->getProcessesNum(), $event->getProcessesNum() == 1 ? 'consumer is' : 'consumers are');

        $this->notify($text);
    }
}