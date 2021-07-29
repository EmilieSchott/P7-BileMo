<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *         "get"={
 *             "normalization_context"={
 *                 "groups"={
 *                     "read_Product_collection",
 *                     "skip_null_values"=false
 *                 },
 *             },
 *         },
 *         "post"={
 *             "denormalization_context"={
 *                 "groups"={
 *                     "write_Product_item",
 *                 },
 *             },
 *         },
 *     },
 *     itemOperations={
 *         "get"={
 *             "normalization_context"={
 *                 "groups"={
 *                     "read_Product_item",
 *                 },
 *                 "skip_null_values"=false
 *             },
 *         },
 *         "delete",
 *         "patch"={
 *             "denormalization_context"={
 *                 "groups"={
 *                     "write_Product_item",
 *                 },
 *             },
 *         },
 *     },
 * )
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read_Product_collection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Groups({"read_Product_collection", "read_Product_item", "write_Product_item"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     * @Groups({"read_Product_collection"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=45)
     * @Groups({"read_Product_collection", "read_Product_item", "write_Product_item"})
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=15)
     * @Groups({"read_Product_collection", "read_Product_item", "write_Product_item"})
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read_Product_collection", "read_Product_item", "write_Product_item"})
     */
    private $stock;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read_Product_item", "write_Product_item"})
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read_Product_collection", "read_Product_item", "write_Product_item"})
     */
    private $imageUrl;

    /**
     * @ORM\Column(type="string", length=45)
     * @Groups({"read_Product_item", "write_Product_item"})
     */
    private $operatingSystem;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Groups({"read_Product_item", "write_Product_item"})
     */
    private $stockageCapacity;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Groups({"read_Product_item", "write_Product_item"})
     */
    private $screenSize;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Groups({"read_Product_item", "write_Product_item"})
     */
    private $photoResolution;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Groups({"read_Product_item", "write_Product_item"})
     */
    private $weight;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getOperatingSystem(): ?string
    {
        return $this->operatingSystem;
    }

    public function setOperatingSystem(string $operatingSystem): self
    {
        $this->operatingSystem = $operatingSystem;

        return $this;
    }

    public function getStockageCapacity(): ?string
    {
        return $this->stockageCapacity;
    }

    public function setStockageCapacity(?string $stockageCapacity): self
    {
        $this->stockageCapacity = $stockageCapacity;

        return $this;
    }

    public function getScreenSize(): ?string
    {
        return $this->screenSize;
    }

    public function setScreenSize(?string $screenSize): self
    {
        $this->screenSize = $screenSize;

        return $this;
    }

    public function getPhotoResolution(): ?string
    {
        return $this->photoResolution;
    }

    public function setPhotoResolution(?string $photoResolution): self
    {
        $this->photoResolution = $photoResolution;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }
}
