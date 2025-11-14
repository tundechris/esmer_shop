<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class WishlistService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addToWishlist(User $user, Product $product): bool
    {
        if (!$user->getWishlist()->contains($product)) {
            $user->addWishlist($product);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }

    public function removeFromWishlist(User $user, Product $product): bool
    {
        if ($user->getWishlist()->contains($product)) {
            $user->removeWishlist($product);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }

    public function isInWishlist(User $user, Product $product): bool
    {
        return $user->getWishlist()->contains($product);
    }

    public function getWishlistCount(User $user): int
    {
        return $user->getWishlist()->count();
    }
}
