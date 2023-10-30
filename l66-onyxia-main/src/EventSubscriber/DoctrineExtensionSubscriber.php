<?php

namespace App\EventSubscriber;

use Gedmo\Blameable\BlameableListener;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class DoctrineExtensionSubscriber implements EventSubscriberInterface
{
    private BlameableListener $blameableListener;
    private TokenStorageInterface $tokenStorage;
    private TranslatableListener $translatableListener;


    public function __construct(
        BlameableListener $blameableListener,
        TokenStorageInterface $tokenStorage,
        TranslatableListener $translatableListener,

    ) {
        $this->blameableListener = $blameableListener;
        $this->tokenStorage = $tokenStorage;
        $this->translatableListener = $translatableListener;

    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($this->tokenStorage !== null &&
            $this->tokenStorage->getToken() !== null &&
            $this->tokenStorage->getToken()->getUser() !== null
        ) {

            $this->blameableListener->setUserValue($this->tokenStorage->getToken()->getUser());
            $locale = $this->getLocaleFromRequestHeader($event);
            $this->translatableListener->setTranslatableLocale($locale);

        }
    }


    private function getLocaleFromRequestHeader(RequestEvent $event): string {
        $allowedLocales = ['pl_PL', 'en_US'];
        $defaultLocale = 'pl_PL';

        $request = $event->getRequest();
        //pobierz header accept-language
        $accept_language = $request->headers->get("accept-language");

        //jeżeli header nie istnieje - zwróc default
        if (empty($accept_language)) {
            return $defaultLocale;
        }
        //Accept-Language może przyjmować wiele wartości, wraz z opcją quality:
        //Accept-Language: fr-CH, fr;q=0.9, en;q=0.8, de;q=0.7, *;q=0.5
        //dlatego rozbijamy tę wartość do tablicy
        $arr = HeaderUtils::split($accept_language, ',;');

        //jeżeli tablica jest pusta - zwróć default
        if (empty($arr[0][0])) {
            return $defaultLocale;
        }
        // Symfony oczekuje podkreślenia zamiast myślnika w locale
        $locale =  str_replace('-', '_', $arr[0][0]);
        return in_array($locale, $allowedLocales) ? $locale : $defaultLocale;
    }
}