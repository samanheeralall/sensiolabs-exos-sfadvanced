<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class AdminVoter implements VoterInterface
{
    public function __construct(
        private readonly RoleHierarchyInterface $hierarchy,
    ) {}

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return self::ACCESS_ABSTAIN;
        }

        if (\in_array('ROLE_ADMIN', $this->hierarchy->getReachableRoleNames($token->getRoleNames()), true)) {
            return self::ACCESS_GRANTED;
        }

        return self::ACCESS_ABSTAIN;
    }
}
