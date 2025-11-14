<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 200)]
    private ?string $name = null;

    #[ORM\Column(length: 200, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?string $price = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    #[Assert\PositiveOrZero]
    private ?string $discountPrice = null;

    #[ORM\Column(type: 'json')]
    private array $images = [];

    #[ORM\Column]
    private bool $isFeatured = false;

    #[ORM\Column]
    private bool $isNewArrival = false;

    #[ORM\Column]
    private bool $isActive = true;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $sku = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Brand $brand = null;

    /**
     * @var Collection<int, ProductVariant>
     */
    #[ORM\OneToMany(targetEntity: ProductVariant::class, mappedBy: 'product', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $variants;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'product', cascade: ['remove'])]
    private Collection $reviews;

    public function __construct()
    {
        $this->variants = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getDiscountPrice(): ?string
    {
        return $this->discountPrice;
    }

    public function setDiscountPrice(?string $discountPrice): static
    {
        $this->discountPrice = $discountPrice;
        return $this;
    }

    public function getFinalPrice(): string
    {
        return $this->discountPrice ?? $this->price;
    }

    public function hasDiscount(): bool
    {
        return $this->discountPrice !== null && $this->discountPrice < $this->price;
    }

    public function getDiscountPercentage(): ?int
    {
        if (!$this->hasDiscount()) {
            return null;
        }

        return (int) round((1 - ($this->discountPrice / $this->price)) * 100);
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): static
    {
        $this->images = $images;
        return $this;
    }

    public function addImage(string $image): static
    {
        if (!in_array($image, $this->images, true)) {
            $this->images[] = $image;
        }
        return $this;
    }

    public function removeImage(string $image): static
    {
        $key = array_search($image, $this->images, true);
        if ($key !== false) {
            unset($this->images[$key]);
            $this->images = array_values($this->images);
        }
        return $this;
    }

    public function getMainImage(): ?string
    {
        return $this->images[0] ?? null;
    }

    public function isFeatured(): bool
    {
        return $this->isFeatured;
    }

    public function setFeatured(bool $isFeatured): static
    {
        $this->isFeatured = $isFeatured;
        return $this;
    }

    public function isNewArrival(): bool
    {
        return $this->isNewArrival;
    }

    public function setNewArrival(bool $isNewArrival): static
    {
        $this->isNewArrival = $isNewArrival;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): static
    {
        $this->sku = $sku;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): static
    {
        $this->brand = $brand;
        return $this;
    }

    /**
     * @return Collection<int, ProductVariant>
     */
    public function getVariants(): Collection
    {
        return $this->variants;
    }

    public function addVariant(ProductVariant $variant): static
    {
        if (!$this->variants->contains($variant)) {
            $this->variants->add($variant);
            $variant->setProduct($this);
        }

        return $this;
    }

    public function removeVariant(ProductVariant $variant): static
    {
        if ($this->variants->removeElement($variant)) {
            if ($variant->getProduct() === $this) {
                $variant->setProduct(null);
            }
        }

        return $this;
    }

    public function getTotalStock(): int
    {
        $total = 0;
        foreach ($this->variants as $variant) {
            $total += $variant->getStock();
        }
        return $total;
    }

    public function isInStock(): bool
    {
        return $this->getTotalStock() > 0;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setProduct($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            if ($review->getProduct() === $this) {
                $review->setProduct(null);
            }
        }

        return $this;
    }

    public function getAverageRating(): float
    {
        if ($this->reviews->isEmpty()) {
            return 0;
        }

        $total = 0;
        foreach ($this->reviews as $review) {
            $total += $review->getRating();
        }

        return round($total / $this->reviews->count(), 1);
    }

    public function getReviewsCount(): int
    {
        return $this->reviews->count();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    public function setIsFeatured(bool $isFeatured): static
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    public function setIsNewArrival(bool $isNewArrival): static
    {
        $this->isNewArrival = $isNewArrival;

        return $this;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }
}
