<?php


namespace App\EntityListener;


use App\Entity\User;
use Doctrine\ORM\Mapping\PrePersist;

class UserListener
{
    /** @PrePersist */
    public function prePersistHandler(User $user)
    {
        $user->setRoles(["ROLE_USER","TRICK_EDIT","TRICK_DELETE","TRICK_NEW"]);
    }
}