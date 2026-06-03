<?php

namespace App\EventSubscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Uid\Uuid;

class JWTAuthenticationSuccessSubscriber implements EventSubscriberInterface
{
    private int $tokenTtl;

    public function __construct(int $tokenTtl)
    {
        $this->tokenTtl = $tokenTtl;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_authentication_success' => 'onAuthenticationSuccess',
        ];
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        $now = new \DateTimeImmutable();
        $expiresAt = $now->modify(sprintf('+%d seconds', $this->tokenTtl));

        $data['refresh_token'] = Uuid::v4()->toRfc4122();
        $data['token_expires_at'] = $expiresAt->getTimestamp();
        $data['token_expires_in'] = $this->tokenTtl;
        $data['user_uid'] = method_exists($user, 'getId') ? $user->getId() : $user->getUserIdentifier();

        $event->setData($data);
    }
}
