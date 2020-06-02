<?php

declare(strict_types=1);

namespace App\Security\Authorization\Voter;

use App\Entity\Machine;
use App\Entity\User;
use App\Exception\Machine\MachineEnoughProductException;
use App\Security\Role;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class MachineVoter extends BaseVoter
{
    public const MACHINE_READ = 'MACHINE_READ';
    public const MACHINE_UPDATE_WALLET = 'MACHINE_UPDATE_WALLET';
    public const MACHINE_PATCH_RETURN_WALLET = 'MACHINE_PATCH_RETURN_WALLET';
    public const MACHINE_UPDATE_CASH = 'MACHINE_UPDATE_CASH';
    public const MACHINE_PATCH_RETURN_CASH = 'MACHINE_PATCH_RETURN_CASH';

    protected function supports(string $attribute, $subject): bool
    {
        return \in_array($attribute, $this->getSupportedAttributes(), true);
    }

    /**
     * @param User|null $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $tokenUser */
        $tokenUser = $token->getUser();

        if (self::MACHINE_READ === $attribute) {
            if (null !== $subject) {
                /**
                 * @var Machine $subject
                 */
                $numProducts = $subject->getProducts()->count();

                if (2 > $numProducts) {
                    MachineEnoughProductException::notMinimum($numProducts);
                }
            }

            return true;
        }

        if (\in_array($attribute, [self::MACHINE_UPDATE_CASH, self::MACHINE_PATCH_RETURN_CASH])) {
            return $this->security->isGranted(Role::ROLE_ADMIN);
        }

        return false;
    }

    private function getSupportedAttributes(): array
    {
        return [
            self::MACHINE_READ,
            self::MACHINE_UPDATE_WALLET,
            self::MACHINE_PATCH_RETURN_WALLET,
            self::MACHINE_UPDATE_CASH,
        ];
    }
}
