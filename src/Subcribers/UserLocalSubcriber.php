<?php

namespace App\Subcribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class UserLocalSubcriber implements EventSubscriberInterface
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => [
                ['onLogin', 15]
            ]
        ];
    }

    public function onLogin(InteractiveLoginEvent $event){
        $user = $event->getAuthenticationToken()->getUser();
        if(!is_null($user->getLocale())){
            $this->requestStack->getSession()->set('_locale', $user->getLocale());
        }
    }

}