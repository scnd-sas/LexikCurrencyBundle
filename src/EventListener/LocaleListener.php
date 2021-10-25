<?php

namespace Lexik\Bundle\CurrencyBundle\EventListener;

use Lexik\Bundle\CurrencyBundle\Currency\FormatterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Cédric Girard <c.girard@lexik.fr>
 */
class LocaleListener implements EventSubscriberInterface
{
    private $formatter;

    public function __construct(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['setCurrencyFormatterLocale', 17] // must be registered before the default Locale listener
            ],
        ];
    }

    public function setCurrencyFormatterLocale(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$request->hasPreviousSession()) {
            return;
        }

        $this->formatter->setLocale($request->getLocale());
    }
}
