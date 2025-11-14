<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\ProductVariant;
use App\Entity\User;
use App\Repository\CartRepository;
use App\Repository\ProductVariantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private EntityManagerInterface $entityManager;
    private CartRepository $cartRepository;
    private ProductVariantRepository $variantRepository;
    private RequestStack $requestStack;

    public function __construct(
        EntityManagerInterface $entityManager,
        CartRepository $cartRepository,
        ProductVariantRepository $variantRepository,
        RequestStack $requestStack
    ) {
        $this->entityManager = $entityManager;
        $this->cartRepository = $cartRepository;
        $this->variantRepository = $variantRepository;
        $this->requestStack = $requestStack;
    }

    /**
     * Get or create cart for current user/session
     */
    public function getCart(?User $user = null): Cart
    {
        if ($user) {
            $cart = $this->cartRepository->findOneBy(['user' => $user]);
            if (!$cart) {
                $cart = new Cart();
                $cart->setUser($user);
                $this->entityManager->persist($cart);
                $this->entityManager->flush();
            }
        } else {
            // For guest users, use session
            $session = $this->requestStack->getSession();
            $sessionId = $session->getId();

            $cart = $this->cartRepository->findOneBy(['sessionId' => $sessionId]);
            if (!$cart) {
                $cart = new Cart();
                $cart->setSessionId($sessionId);
                $this->entityManager->persist($cart);
                $this->entityManager->flush();
            }
        }

        return $cart;
    }

    /**
     * Add item to cart
     */
    public function addItem(?User $user, int $variantId, int $quantity = 1): bool
    {
        $variant = $this->variantRepository->find($variantId);
        if (!$variant) {
            return false;
        }

        // Check stock availability
        if ($variant->getStock() < $quantity) {
            return false;
        }

        $cart = $this->getCart($user);

        // Check if item already exists in cart
        $existingItem = null;
        foreach ($cart->getItems() as $item) {
            if ($item->getVariant()->getId() === $variantId) {
                $existingItem = $item;
                break;
            }
        }

        if ($existingItem) {
            // Update quantity
            $newQuantity = $existingItem->getQuantity() + $quantity;
            if ($variant->getStock() < $newQuantity) {
                return false;
            }
            $existingItem->setQuantity($newQuantity);
        } else {
            // Create new cart item
            $cartItem = new CartItem();
            $cartItem->setCart($cart);
            $cartItem->setVariant($variant);
            $cartItem->setQuantity($quantity);
            $cartItem->setPriceAtAddition($variant->getProduct()->getFinalPrice());

            $this->entityManager->persist($cartItem);
        }

        $this->entityManager->flush();
        return true;
    }

    /**
     * Update cart item quantity
     */
    public function updateItemQuantity(?User $user, int $cartItemId, int $quantity): bool
    {
        $cart = $this->getCart($user);

        foreach ($cart->getItems() as $item) {
            if ($item->getId() === $cartItemId) {
                // Check stock
                if ($item->getVariant()->getStock() < $quantity) {
                    return false;
                }

                $item->setQuantity($quantity);
                $this->entityManager->flush();
                return true;
            }
        }

        return false;
    }

    /**
     * Remove item from cart
     */
    public function removeItem(?User $user, int $cartItemId): bool
    {
        $cart = $this->getCart($user);

        foreach ($cart->getItems() as $item) {
            if ($item->getId() === $cartItemId) {
                $this->entityManager->remove($item);
                $this->entityManager->flush();
                return true;
            }
        }

        return false;
    }

    /**
     * Clear entire cart
     */
    public function clearCart(?User $user): void
    {
        $cart = $this->getCart($user);

        foreach ($cart->getItems() as $item) {
            $this->entityManager->remove($item);
        }

        $this->entityManager->flush();
    }

    /**
     * Get cart total
     */
    public function getCartTotal(?User $user): string
    {
        $cart = $this->getCart($user);
        return $cart->getTotal();
    }

    /**
     * Get cart items count
     */
    public function getCartItemsCount(?User $user): int
    {
        $cart = $this->getCart($user);
        return $cart->getTotalItems();
    }

    /**
     * Merge guest cart to user cart after login
     */
    public function mergeGuestCartToUserCart(User $user, string $guestSessionId): void
    {
        $guestCart = $this->cartRepository->findOneBy(['sessionId' => $guestSessionId]);
        if (!$guestCart) {
            return;
        }

        $userCart = $this->getCart($user);

        foreach ($guestCart->getItems() as $guestItem) {
            // Check if item already exists in user cart
            $existingItem = null;
            foreach ($userCart->getItems() as $userItem) {
                if ($userItem->getVariant()->getId() === $guestItem->getVariant()->getId()) {
                    $existingItem = $userItem;
                    break;
                }
            }

            if ($existingItem) {
                // Merge quantities
                $existingItem->setQuantity($existingItem->getQuantity() + $guestItem->getQuantity());
            } else {
                // Move item to user cart
                $guestItem->setCart($userCart);
            }
        }

        // Delete guest cart
        $this->entityManager->remove($guestCart);
        $this->entityManager->flush();
    }
}
