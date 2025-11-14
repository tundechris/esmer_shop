<?php

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 50)]
    private ?string $code = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: ['percentage', 'fixed'])]
    private ?string $type = 'percentage';

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?string $value = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    #[Assert\PositiveOrZero]
    private ?string $minPurchaseAmount = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    #[Assert\PositiveOrZero]
    private ?string $maxDiscountAmount = null;

    #[ORM\Column(nullable: true)]
    private ?int $usageLimit = null;

    #[ORM\Column]
    private int $usageCount = 0;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $validFrom = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $validUntil = null;

    #[ORM\Column]
    private bool $isActive = true;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = strtoupper($code);
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;
        return $this;
    }

    public function getMinPurchaseAmount(): ?string
    {
        return $this->minPurchaseAmount;
    }

    public function setMinPurchaseAmount(?string $minPurchaseAmount): static
    {
        $this->minPurchaseAmount = $minPurchaseAmount;
        return $this;
    }

    public function getMaxDiscountAmount(): ?string
    {
        return $this->maxDiscountAmount;
    }

    public function setMaxDiscountAmount(?string $maxDiscountAmount): static
    {
        $this->maxDiscountAmount = $maxDiscountAmount;
        return $this;
    }

    public function getUsageLimit(): ?int
    {
        return $this->usageLimit;
    }

    public function setUsageLimit(?int $usageLimit): static
    {
        $this->usageLimit = $usageLimit;
        return $this;
    }

    public function getUsageCount(): int
    {
        return $this->usageCount;
    }

    public function setUsageCount(int $usageCount): static
    {
        $this->usageCount = $usageCount;
        return $this;
    }

    public function incrementUsage(): static
    {
        $this->usageCount++;
        return $this;
    }

    public function getValidFrom(): ?\DateTimeImmutable
    {
        return $this->validFrom;
    }

    public function setValidFrom(?\DateTimeImmutable $validFrom): static
    {
        $this->validFrom = $validFrom;
        return $this;
    }

    public function getValidUntil(): ?\DateTimeImmutable
    {
        return $this->validUntil;
    }

    public function setValidUntil(?\DateTimeImmutable $validUntil): static
    {
        $this->validUntil = $validUntil;
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

    public function isValid(?\DateTimeImmutable $date = null): bool
    {
        if (!$this->isActive) {
            return false;
        }

        $date = $date ?? new \DateTimeImmutable();

        if ($this->validFrom && $date < $this->validFrom) {
            return false;
        }

        if ($this->validUntil && $date > $this->validUntil) {
            return false;
        }

        if ($this->usageLimit && $this->usageCount >= $this->usageLimit) {
            return false;
        }

        return true;
    }

    public function canBeUsedForAmount(string $amount): bool
    {
        if (!$this->minPurchaseAmount) {
            return true;
        }

        return $amount >= $this->minPurchaseAmount;
    }

    public function calculateDiscount(string $amount): string
    {
        if ($this->type === 'percentage') {
            $discount = (float)$amount * ((float)$this->value / 100);
        } else {
            $discount = (float)$this->value;
        }

        if ($this->maxDiscountAmount) {
            $discount = min($discount, (float)$this->maxDiscountAmount);
        }

        return (string)round($discount, 2);
    }

    public function __toString(): string
    {
        return $this->code ?? '';
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }
}
