<?php

declare(strict_types=1);

namespace App\Security\Authorization\Voter;

use App\Entity\User;
use App\Security\Role;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class HistorySaleVoter extends BaseVoter
{
    public const HISTORY_SALE_READ = 'HISTORY_SALE_READ';

    protected function supports(string $attribute, $subject): bool
    {
        return \in_array($attribute, $this->getSupportedAttributes(), true);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $tokenUser */
        $tokenUser = $token->getUser();

        if (self::HISTORY_SALE_READ === $attribute) {
            return $this->security->isGranted(Role::ROLE_ADMIN);
        }

        return false;
    }

    private function getSupportedAttributes(): array
    {
        return [
            self::HISTORY_SALE_READ,
        ];
    }
}
