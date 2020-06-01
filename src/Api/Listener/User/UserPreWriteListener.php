<?php

declare(strict_types=1);

namespace App\Api\Listener\User;

use App\Api\Action\RequestTransformer;
use App\Api\Listener\PreWriteListener;
use App\Entity\User;
use App\Security\Validator\Role\RoleValidator;
use App\Service\Password\EncoderService;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class UserPreWriteListener implements PreWriteListener
{
    private const PUT_USER = 'api_users_put_item';

    private EncoderService $encoderService;

    /** @var iterable|RoleValidator[] */
    private $roleValidators;

    public function __construct(EncoderService $encoderService, iterable $roleValidators)
    {
        $this->roleValidators = $roleValidators;
        $this->encoderService = $encoderService;
    }

    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();

        if (self::PUT_USER === $request->get('_route')) {
            /** @var User $user */
            $user = $event->getControllerResult();

            $plainTextPassword = RequestTransformer::getRequiredField($request, 'password');

            $user->setPassword($this->encoderService->generateEncodedPasswordForUser($user, $plainTextPassword));
        }
    }
}
