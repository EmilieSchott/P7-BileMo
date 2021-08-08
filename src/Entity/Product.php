<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @UniqueEntity(
 *     fields="name",
 *     message="Ce produit existe déjà.",
 *     groups={"write_Product_item"}
 * )
 */
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => [
                    'read_Product_collection',
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
                    'write_Product_item',
                ],
            ],
            'validation_groups' => [
                'write_Product_item',
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
                    'read_Product_item',
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
                    'write_Product_item',
                ],
            ],
            'validation_groups' => [
                'write_Product_item',
            ],
            'security' => 'is_granted("ROLE_SUPER_ADMIN")',
            'openapi_context' => [
                'security' => [['bearerAuth' => []]],
            ],
        ],
    ],
    paginationMaximumItemsPerPage: 30,
    paginationClientItemsPerPage: true,
)]
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read_Product_collection'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    #[
        Groups(['read_Product_collection', 'read_Product_item', 'write_Product_item']),
        Assert\NotBlank(
            message: 'Product name should not be blank.',
            groups: ['write_Product_item']
        ),
        Assert\Length(
            max: 150,
            maxMessage: 'Product name is too long. It should have {{ limit }} characters or less.',
            groups: ['write_Product_item']
        ),
        ApiProperty(
            attributes: [
                'openapi_context' => [
                    'example' => 'Product name',
                ],
            ]
        )
    ]
    private $name;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    #[Groups(['read_Product_collection'])]
    private $slug;

    /**
     * @ORM\Column(type="string", length=45)
     */
    #[
        Groups(['read_Product_collection', 'read_Product_item', 'write_Product_item']),
        Assert\NotBlank(
            message: 'Product brand should not be blank.',
            groups: ['write_Product_item']
        ),
        Assert\Length(
            max: 45,
            maxMessage: 'Product brand is too long. It should have {{ limit }} characters or less.',
            groups: ['write_Product_item']
        ),
        ApiProperty(
            attributes: [
                'openapi_context' => [
                    'example' => 'Samsung',
                ],
            ]
        )
    ]
    private $brand;

    /**
     * @ORM\Column(type="string", length=15)
     */
    #[
        Groups(['read_Product_collection', 'read_Product_item', 'write_Product_item']),
        Assert\NotBlank(
            message: 'Product price should not be blank.',
            groups: ['write_Product_item']
        ),
        Assert\Length(
            max: 15,
            maxMessage: 'Product price is too long. It should have {{ limit }} characters or less. (you should indicate currency)',
            groups: ['write_Product_item']
        ),
        ApiProperty(
            attributes: [
                'openapi_context' => [
                    'example' => '230€',
                ],
            ]
        )
    ]
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    #[
        Groups(['read_Product_collection', 'read_Product_item', 'write_Product_item']),
        Assert\NotBlank(
            message : 'Product stock should not be blank.',
            groups: ['write_Product_item']
        ),
        Assert\Length(
            max: 11,
            maxMessage: 'Product stock is too long. It should have {{ limit }} characters or less.',
            groups: ['write_Product_item']
        ),
        ApiProperty(
            attributes: [
                'openapi_context' => [
                    'example' => '75',
                ],
            ]
        )
    ]
    private $stock;

    /**
     * @ORM\Column(type="text")
     */
    #[
        Groups(['read_Product_item', 'write_Product_item']),
        Assert\NotBlank(
            message: 'Product description should not be blank.',
            groups: ['write_Product_item']
        ),
        ApiProperty(
            attributes: [
                'openapi_context' => [
                    'example' => 'Product description',
                ],
            ]
        )

    ]
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    #[
        Groups(['read_Product_collection', 'read_Product_item', 'write_Product_item']),
        Assert\NotBlank(
            message: 'Product image should not be blank.',
            groups: ['write_Product_item']
        ),
        Assert\Url(
            message: 'Url is not valid.',
            groups: ['write_Product_item']
        ),
        ApiProperty(
            attributes: [
                'openapi_context' => [
                    'example' => 'https://link-to-image.com',
                ],
            ]
        )
    ]
    private $imageUrl;

    /**
     * @ORM\Column(type="string", length=45)
     */
    #[
        Groups(['read_Product_item', 'write_Product_item']),
        Assert\NotBlank(
            message: 'Product operating system should not be blank.',
            groups: ['write_Product_item']
        ),
        Assert\Length(
            max: 45,
            maxMessage: 'Product operating system is too long. It should have {{ limit }} characters or less.',
            groups: ['write_Product_item']
        ),
        ApiProperty(
            attributes: [
                'openapi_context' => [
                    'example' => 'Android',
                ],
            ]
        )
    ]
    private $operatingSystem;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    #[Groups(['read_Product_item', 'write_Product_item']),
    Assert\Length(
        max: 15,
        maxMessage: 'Memory capacity is too long. It should have {{ limit }} characters or less.',
        groups: ['write_Product_item']
    ),
    ApiProperty(
        attributes: [
            'openapi_context' => [
                'example' => '16Go',
            ],
        ]
    )
    ]
    private $stockageCapacity;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    #[
        Groups(['read_Product_item', 'write_Product_item']),
        Assert\Length(
            max: 15,
            maxMessage: 'Screen size is too long. It should have {{ limit }} characters or less. (You should indicate unit)',
            groups: ['write_Product_item']
        ),
        ApiProperty(
            attributes: [
                'openapi_context' => [
                    'example' => '16 pouces',
                ],
            ]
        )
    ]
    private $screenSize;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    #[
        Groups(['read_Product_item', 'write_Product_item']),
        Assert\Length(
            max: 15,
            maxMessage: 'Photo resolution is too long. It should have {{ limit }} characters or less. (You should indicate unit)',
            groups: ['write_Product_item']
        ),
        ApiProperty(
            attributes: [
                'openapi_context' => [
                    'example' => '125Mpx',
                ],
            ]
        )
    ]
    private $photoResolution;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    #[
        Groups(['read_Product_item', 'write_Product_item']),
        Assert\Length(
            max: 15,
            maxMessage: 'Weight is too long. It should have {{ limit }} characters or less. (You should indicate unit)',
            groups: ['write_Product_item']
        ),
        ApiProperty(
            attributes: [
                'openapi_context' => [
                    'example' => '230g',
                ],
            ]
        )
    ]
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
