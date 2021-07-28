<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *         "get"={
 *             "normalization_context"={
 *                 "groups"={
 *                     "read_Client_collection",
 *                 },
 *             },
 *         },
 *         "post"={
 *             "denormalization_context"={
 *                 "groups"={
 *                     "write_Client_item",
 *                 },
 *             },
 *         },
 *     },
 *     itemOperations={
 *         "get"={
 *             "normalization_context"={
 *                 "groups"={
 *                     "read_Client_item",
 *                 },
 *             },
 *         },
 *         "delete",
 *         "patch"={
 *             "denormalization_context"={
 *                 "groups"={
 *                     "write_Client_item",
 *                 },
 *             },
 *         },
 *     },
 * )
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read_Client_collection", "read_User_collection", "read_User_item" })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=75)
     * @Groups({"read_Client_collection", "read_Client_item", "read_User_collection", "read_User_item", "write_Client_item" })
     */
    private $companyName;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"read_Client_item", "write_Client_item" })
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read_Client_item", "write_Client_item" })
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=45)
     * @Groups({"read_Client_item", "write_Client_item" })
     */
    private $SiretNumber;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="client")
     * @Groups({"read_Client_item"})
     */
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
