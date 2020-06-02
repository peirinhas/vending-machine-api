<?php

declare(strict_types=1);

namespace App\Security\Authorization\Voter;

use App\Entity\Product;
use App\Entity\User;
use App\Security\Role;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ProductVoter extends BaseVoter
{
    public const PRODUCT_READ = 'PRODUCT_READ';
    public const PRODUCT_CREATE = 'PRODUCT_CREATE';
    public const PRODUCT_UPDATE = 'PRODUCT_UPDATE';
    public const PRODUCT_DELETE = 'PRODUCT_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return \in_array($attribute, $this->getSupportedAttributes(), true);
    }

    /**
     * @param Product|null $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $tokenUser */
        $tokenUser = $token->getUser();

        if (\in_array($attribute, [self::PRODUCT_READ, self::PRODUCT_CREATE])) {
            return true;
        }

        if (\in_array($attribute, [self::PRODUCT_UPDATE, self::PRODUCT_DELETE])) {
            if (null !== $machine = $subject->getMachine()) {
                return $this->security->isGranted(Role::ROLE_ADMIN) || $subject->isOwnedByMachine($tokenUser);
            }

            return $this->security->isGranted(Role::ROLE_ADMIN);
        }

        return false;
    }

    private function getSupportedAttributes(): array
    {
        return [
            self::PRODUCT_READ,
            self::PRODUCT_CREATE,
            self::PRODUCT_UPDATE,
            self::PRODUCT_DELETE,
        ];
    }
}
