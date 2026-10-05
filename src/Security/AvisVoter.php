<?php

namespace App\Security;

use App\Entity\Avis;
use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class AvisVoter extends Voter
{
    public const EDIT = 'AVIS_EDIT';
    public const DELETE = 'AVIS_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE], true)
            && $subject instanceof Avis;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $utilisateur = $token->getUser();

        if (!$utilisateur instanceof Utilisateur || !$subject instanceof Avis) {
            return false;
        }

        return $utilisateur->getId() !== null
            && $utilisateur->getId() === $subject->getUtilisateur()?->getId();
    }
}
