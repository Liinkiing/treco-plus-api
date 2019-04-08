<?php

namespace App\Security\Voter;

use App\Entity\Team;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class TeamVoter extends Voter
{
    public const VIEW = 'VIEW';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject): bool
    {
        return in_array($attribute, [self::VIEW], true)
            && $subject instanceof Team;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($subject, $user);
                break;
        }

        return false;
    }

    private function canView(Team $team, User $user): bool
    {
        return $this->security->isGranted('ROLE_SUPER_ADMIN') ||
            $team->getOwner() === $user ||
            $team->getMembers()->contains($user);
    }
}
