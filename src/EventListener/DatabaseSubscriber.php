<?php


namespace App\EventListener;

use App\Entity\Token;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class DatabaseSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->logActivity('prePersist', $args);
    }

    private function logActivity(string $action, LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Token) {
            return;
        }

        if (null !== $entity->getToken()) {
            $entity->setDate(new \DateTimeImmutable());
        }
    }
}
