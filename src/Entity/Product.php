<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $imageUrl;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $operatingSystem;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $stockageCapacity;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $screenSize;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $photoResolution;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
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
