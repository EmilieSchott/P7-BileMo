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
        ],
        'delete',
        'patch' => [
            'denormalization_context' => [
                'groups' => [
                    'write_Client_item',
                ],
            ],
            'validation_groups' => [
                'write_Client_item',
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
    #[Groups(['read_Client_collection', 'read_User_collection', 'read_User_item'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=75)
     */
    #[
        Groups(['read_Client_collection', 'read_Client_item', 'read_User_collection', 'read_User_item', 'write_Client_item']),
        Assert\NotBlank(
            message: "Vous devez indiquer le nom de l'entreprise.",
            groups: ['write_Client_item']
        ),
        Assert\Length(
            max: 75,
            maxMessage: "Le nom de l'entreprise doit faire maximum {{ limit }} caractères.",
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
            maxMessage: "Le numéro de téléphone de l'entreprise doit faire maximum {{ limit }} caractères.",
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
            message: "Vous devez indiquer une adresse pour l'entreprise.",
            groups: ['write_Client_item']
        ),
        Assert\Length(
            max: 255,
            maxMessage: "L'adresse de l'entreprise doit faire maximum {{ limit }} caractères.",
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
            message: "Vous devez indiquer le numéro SIRET de l'entreprise.",
            groups: ['write_Client_item']
        ),
        Assert\Length(
            max: 45,
            maxMessage: "Le numéro SIRET de l'entreprise doit faire maximum {{ limit }} caractères.",
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
