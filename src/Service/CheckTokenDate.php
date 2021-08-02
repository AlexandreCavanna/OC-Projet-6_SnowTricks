<?php


namespace App\Service;

use App\Entity\User;

class CheckTokenDate
{
    /**
     * @throws \Exception
     */
    public function checkTokenDate(?User $user, string $timeDuration): bool
    {
        if (!is_null($user)) {
            $currentTime = new \DateTimeImmutable();
            $expireTime = new \DateTimeImmutable($timeDuration);
            $tokenTime = $user->getToken()->getDate();

            if ($expireTime <= $tokenTime && $tokenTime <= $currentTime) {
                return true;
            }
        }
        return false;
    }
}
