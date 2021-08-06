<?php


namespace App\Service;

use App\Entity\User;

class CheckTokenDate
{
    /**
     * @throws \Exception
     */
    public function isTokenDateValid(User $user, string $timeDuration): bool
    {
        if ($user) {
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
