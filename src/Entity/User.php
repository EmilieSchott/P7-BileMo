<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Controller\MyDatasController;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *    fields="email",
 *    message="Cette adresse mail est déjà utilisée.",
 *    groups={"write_User_item"}
 * )
 */
#[ApiResource(
    collectionOperations : [
        'my_datas' => [
            'security' => 'is_granted("ROLE_USER")',
            'pagination_enabled' => false,
            'method' => 'GET',
            'path' => '/users/my-datas',
            'controller' => MyDatasController::class,
            'read' => false,
            'openapi_context' => [
                'security' => [['bearerAuth' => []]],
                'summary' => 'Get datas on the authenticated user',
                'description' => 'Get id, email and roles for the authenticated user',
                'responses' => [
                    '200' => [
                        'description' => 'Get datas registered in the JWT token used for authentication.',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'id' => ['type' => 'integer'],
                                        'roles' => ['type' => 'array'],
                                        'email' => ['type' => 'string'],
                                        'userIdentifier' => ['type' => 'string'],
                                        'username' => ['type' => 'string'],
                                    ],
                                ],
                                'example' => [
                                    'id' => 1,
                                    'roles' => ['ROLE_USER'],
                                    'email' => 'johndoe@example.com',
                                    'userIdentifier' => 'johndoe@example.com',
                                    'username' => 'johndoe@example.com',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'get' => [
            'security' => 'is_granted("ROLE_ADMIN")',
            'normalization_context' => [
                'groups' => [
                    'read_User_collection',
                ],
                'skip_null_values' => false,
            ],
            'openapi_context' => [
                'security' => [['bearerAuth' => []]],
            ],
        ],
        'post' => [
            'denormalization_context' => [
                'groups' => [
                    'write_User_item',
                ],
            ],
            'validation_groups' => [
                'write_User_item',
            ],
        ],
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => [
                    'read_User_item',
                ],
                'skip_null_values' => false,
            ],
        ],
        'delete',
        'patch' => [
            'denormalization_context' => [
                'groups' => [
                    'write_User_item',
                ],
            ],
            'validation_groups' => [
                'write_User_item',
            ],
        ],
    ],
    paginationMaximumItemsPerPage: 30,
    paginationClientItemsPerPage: true,
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, JWTUserInterface
{
    const ROLES = [['ROLE_USER'], ['ROLE_ADMIN'], ['ROLE_SUPER_ADMIN']];

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
    #[
        Groups(['read_Client_item', 'read_User_collection', 'read_User_item', 'write_User_item']),
        Assert\NotBlank(
            message: 'Vous devez indiquer un prénom.',
            groups: ['write_User_item']
        ),
        Assert\Length(
            max: 25,
            maxMessage: 'Le prénom doit faire maximum {{ limit }} caractères.',
            groups: ['write_User_item']
        )
    ]
    private $firstName;

    /**
     * @ORM\Column(type="string", length=45)
     */
    #[
        Groups(['read_Client_item', 'read_User_collection', 'read_User_item', 'write_User_item']),
        Assert\NotBlank(
            message: 'Vous devez indiquer un nom de famille.',
            groups: ['write_User_item']
        ),
        Assert\Length(
            max: 45,
            maxMessage: 'Le nom de famille doit faire maximum {{ limit }} caractères.',
            groups: ['write_User_item']
        )
    ]
    private $lastName;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="users")
     */
    #[Groups(['read_User_collection', 'read_User_item', 'write_User_item'])]
    private $client;

    /**
     * @ORM\Column(type="json")
     */
    #[
        Groups(['read_Client_item', 'read_User_collection', 'read_User_item', 'write_User_item']),
        Assert\Choice(
            choices: User::ROLES,
            groups: ['write_User_item'],
            message: "{{ value }} n'est pas un choix valide. En fonction de votre propre niveau d'acréditation, vous pouvez attribuer : {{ choices }}."
        )
    ]
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    #[
        Groups(['read_User_item', 'write_User_item']),
        Assert\Length(
            max: 20,
            maxMessage: 'Le numéro de téléphone doit faire maximum {{ limit }} caractères.',
            groups: ['write_User_item']
        )
    ]
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    #[
        Groups(['read_User_item', 'write_User_item']),
        Assert\NotBlank(
            message: 'Vous devez indiquer un email.',
            groups: ['write_User_item']
        ),
        Assert\Email(
            message: "L'email indiqué n'est pas valide.",
            groups: ['write_User_item']
        ),
        Assert\Length(
            max: 180,
            maxMessage: "L'email doit faire maximum {{ limit }} caractères.",
            groups: ['write_User_item']
        )
    ]
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    #[
        Groups(['write_User_item']),
        Assert\NotBlank(
            message: 'Vous devez indiquer un mot de passe.',
            groups: ['write_User_item']
        )
    ]
    private $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
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
    public function getPassword(): ?string
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

    public static function createFromPayload($id, array $payload)
    {
        $user = new User();
        $user->setId($id);
        $user->setRoles($payload['roles']);
        $user->setEmail($payload['userIdentifier']);

        return $user;
    }
}
