<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @UniqueEntity(
 *     fields={"companyName", "address"},
 *     message="Ce client existe déjà.",
 *     groups={"write_Client_item"}
 * )
 */
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => [
                    'read_Client_collection',
                ],
                'skip_null_values' => false,
            ],
            'security' => 'is_granted("ROLE_USER")',
            'openapi_context' => [
                'security' => [['bearerAuth' => []]],
            ],
        ],
        'post' => [
            'denormalization_context' => [
                'groups' => [
                    'write_Client_item',
                ],
            ],
            'validation_groups' => [
                'write_Client_item',
            ],
            'security' => 'is_granted("ROLE_SUPER_ADMIN")',
            'openapi_context' => [
                'security' => [['bearerAuth' => []]],
            ],
        ],
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => [
                    'read_Client_item',
                ],
                'skip_null_values' => false,
            ],
            'security' => 'is_granted("ROLE_USER")',
            'openapi_context' => [
                'security' => [['bearerAuth' => []]],
            ],
        ],
        'delete' => [
            'security' => 'is_granted("ROLE_SUPER_ADMIN")',
            'openapi_context' => [
                'security' => [['bearerAuth' => []]],
            ],
        ],
        'patch' => [
            'denormalization_context' => [
                'groups' => [
                    'write_Client_item',
                ],
            ],
            'validation_groups' => [
                'write_Client_item',
            ],
            'security' => 'is_granted("ROLE_ADMIN")',
            'openapi_context' => [
                'security' => [['bearerAuth' => []]],
            ],
        ],
    ],
    paginationMaximumItemsPerPage: 30,
    paginationClientItemsPerPage: true,
)]
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read_Client_collection', 'read_User_collection', 'read_User_item', 'read_token'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=75)
     */
    #[
        Groups(['read_Client_collection', 'read_Client_item', 'read_User_collection', 'read_User_item', 'write_Client_item', 'read_token']),
        Assert\NotBlank(
            message: 'Company name should not be blank.',
            groups: ['write_Client_item']
        ),
        Assert\Length(
            max: 75,
            maxMessage: 'Company name is too long. It should have {{ limit }} characters or less. (You should indicate unit)',
            groups: ['write_Client_item']
        )
    ]
    private $companyName;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    #[Groups(
        ['read_Client_item', 'write_Client_item']
    ),
        Assert\Length(
            max: 20,
            maxMessage: 'Phone number is too long. It should have {{ limit }} characters or less. (You should indicate unit)',
            groups: ['write_Client_item']
        )
    ]
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Groups(['read_Client_item', 'read_Client_collection', 'write_Client_item']),
        Assert\NotBlank(
            message: 'Company address should not be blank.',
            groups: ['write_Client_item']
        ),
        Assert\Length(
            max: 255,
            maxMessage: 'Company address is too long. It should have {{ limit }} characters or less. (You should indicate unit)',
            groups: ['write_Client_item']
        )
    ]
    private $address;

    /**
     * @ORM\Column(type="string", length=45)
     */
    #[
        Groups(['read_Client_item', 'write_Client_item']),
        Assert\NotBlank(
            message: 'Company SIRET number should not be blank.',
            groups: ['write_Client_item']
        ),
        Assert\Length(
            max: 45,
            maxMessage: 'Company SIRET number is too long. It should have {{ limit }} characters or less. (You should indicate unit)',
            groups: ['write_Client_item']
        )
    ]
    private $SiretNumber;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="client")
     */
    #[Groups(['read_Client_item'])]
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getSiretNumber(): ?string
    {
        return $this->SiretNumber;
    }

    public function setSiretNumber(string $SiretNumber): self
    {
        $this->SiretNumber = $SiretNumber;

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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setClient($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getClient() === $this) {
                $user->setClient(null);
            }
        }

        return $this;
    }
}
