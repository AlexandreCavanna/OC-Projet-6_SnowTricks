<?php

namespace App\Security\Voter;

use App\Entity\Comment;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends Voter
{
    private const COMMENT_DELETE = 'COMMENT_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::COMMENT_DELETE])
            && $subject instanceof Comment;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        $comment = $subject;

        switch ($attribute) {
            case self::COMMENT_DELETE:
                return $this->canDelete($comment, $user);
        }

        return false;
    }

    private function canDelete(Comment $comment, UserInterface $user): bool
    {
        return $user === $comment->getUser();
    }
}
