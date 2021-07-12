<?php

namespace App\Security\Voter;

use App\Entity\Trick;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TrickVoter extends Voter
{
    private const TRICK_NEW = 'TRICK_NEW';

    private const TRICK_EDIT = 'TRICK_EDIT';

    private const TRICK_DELETE = 'TRICK_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::TRICK_NEW, self::TRICK_EDIT, self::TRICK_DELETE])
            && $subject instanceof Trick || $subject === null;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        $trick = $subject;

        switch ($attribute) {
            case self::TRICK_NEW:
                return $this->canCreate();
            case self::TRICK_EDIT:
                return $this->canEdit($trick, $user);
            case self::TRICK_DELETE:
                return $this->canDelete($trick, $user);
        }

        return false;
    }

    private function canEdit(Trick $trick, User $user): bool
    {
        return $user === $trick->getUser();
    }

    private function canCreate(): bool
    {
        return true;
    }

    private function canDelete(Trick $trick, User $user): bool
    {
        if ($this->canEdit($trick, $user)) {
            return true;
        }

        return false;
    }
}
