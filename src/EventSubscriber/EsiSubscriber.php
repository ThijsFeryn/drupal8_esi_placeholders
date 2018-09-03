<?php

namespace Drupal\esi_placeholders\EventSubscriber;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpCache\Esi;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EsiSubscriber implements EventSubscriberInterface
{
    /**
     * @var Symfony\Component\HttpKernel\HttpCache\Esi
     */
    protected $esi;

    /**
     * @param Symfony\Component\HttpKernel\HttpCache\Esi $esi
     */
    public function __construct(Esi $esi)
    {
        $this->esi = $esi;
    }

    /**
     * @param FilterResponseEvent $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onRespond(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        if($this->esi->hasSurrogateCapability($request)){
            $this->esi->addSurrogateControl($response);
            return $response;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        $events[KernelEvents::RESPONSE][] = ['onRespond', -10000];
        return $events;
    }

}
