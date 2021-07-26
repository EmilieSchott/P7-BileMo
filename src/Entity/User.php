<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
#[ApiResource(
    collectionOperations : [
        'get' => [
            'normalization_context' => [
                'groups' => [
                    'read_User_collection',
                ],
            ],
        ],
        'post' => [
            'denormalization_context' => [
                'groups' => [
                    'write_User_item',
                ],
            ],
        ],
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => [
                    'read_User_item',
                ],
            ],
        ],
        'delete',
        'patch' => [
            'denormalization_context' => [
                'groups' => [
                    'write_User_item',
                ],
            ],
        ],
    ],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read_Client_item', 'read_User_collection'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    #[Groups(['read_Client_item', 'read_User_collection', 'read_User_item', 'write_User_item'])]
    private $firstName;

    /**
     * @ORM\Column(type="string", length=45)
     */
    #[Groups(['read_Client_item', 'read_User_collection', 'read_User_item', 'write_User_item'])]
    private $lastName;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="users")
     */
    #[Groups(['read_User_collection','read_User_item', 'write_User_item'])]
    private $client;

    /**
     * @ORM\Column(type="json")
     */
    #[Groups(['read_Client_item', 'read_User_collection', 'read_User_item', 'write_User_item'])]
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    #[Groups(['read_User_item', 'write_User_item'])]
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    #[Groups(['read_User_item', 'write_User_item'])]
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    #[Groups(['write_User_item'])]
    private $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
