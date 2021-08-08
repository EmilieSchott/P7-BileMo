<?php

namespace App\Serializer;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class LinkedToClientDenormalizer implements ContextAwareDenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private const ALREADY_CALLED_DENORMALIZER = 'LinkedToClientDenormalizerCalled';

    public function __construct(private Security $security, private ClientRepository $clientRepository, private UserPasswordHasherInterface $hasher, private UserRepository $userRepository)
    {
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        $alreadyCalled = $data[self::ALREADY_CALLED_DENORMALIZER] ?? false;
        if (User::class === $type && false === $alreadyCalled) {
            return true;
        }
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $data[self::ALREADY_CALLED_DENORMALIZER] = true;
        $userToPersist = $this->denormalizer->denormalize($data, $type, $format, $context);
        $authenticatedUser = $this->security->getUser();
        if ($authenticatedUser instanceof User) {
            if (null !== $userToPersist->getPassword()) {
                $plaintextPassword = $userToPersist->getPassword();
                $hashedPassword = $this->hasher->hashPassword($userToPersist, $plaintextPassword);
                $userToPersist->setPassword($hashedPassword);
            }

            $authenticatedUserRoles = $authenticatedUser->getRoles();
            if (isset($context['collection_operation_name']) && 'post' === $context['collection_operation_name'] && \in_array('ROLE_ADMIN', $authenticatedUserRoles, true)) {
                $userToPersist->setRoles(['ROLE_USER']);
                if (null !== $authenticatedUser->getClient()) {
                    $authenticatedUserClient = $this->clientRepository->findOneBy(['id' => $authenticatedUser->getClient()->getId()]);
                    $userToPersist->setClient($authenticatedUserClient);
                }
            }

            if (isset($context['item_operation_name']) && 'patch' === $context['item_operation_name'] && !\in_array('ROLE_SUPER_ADMIN', $authenticatedUserRoles, true)) {
                $userToModify = $this->userRepository->findOneBy(['id' => $context['object_to_populate']->getId()]);

                if (null !== $userToPersist->getClient()) {
                    $userToModifyOldClient = $this->clientRepository->findOneBy(['id' => $userToModify->getClient()->getId()]);
                    $userToPersist->setClient($userToModifyOldClient);
                }

                if (null !== $userToPersist->getRoles()) {
                    $userToModifyOldRoles = $userToModify->getRoles();
                    $userToPersist->setRoles($userToModifyOldRoles);
                }
            }
        }

        return $userToPersist;
    }
}
