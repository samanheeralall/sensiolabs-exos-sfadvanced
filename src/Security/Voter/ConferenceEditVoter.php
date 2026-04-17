<?php

namespace App\Security\Voter;

use App\Entity\Conference;
use App\Entity\User;
use App\Registry\ConferenceAttributes;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class ConferenceEditVoter implements VoterInterface
{

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return self::ACCESS_DENIED;
        }

        foreach ($attributes as $attribute) {
            if (
                $attribute !== ConferenceAttributes::EDIT_CONF
                || !$subject instanceof Conference
            ) {
                return self::ACCESS_ABSTAIN;
            }

            if ($user === $subject->getCreatedBy()) {
                return self::ACCESS_GRANTED;
            }
        }

        return self::ACCESS_DENIED;
    }
}
