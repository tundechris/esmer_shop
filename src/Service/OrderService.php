<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createOrderFromCart(User $user, Cart $cart, array $shippingData): Order
    {
        $order = new Order();
        $order->setUser($user);
        $order->setStatus('pending');
        $order->setPaymentStatus('pending');

        // Set shipping address
        $order->setShippingFirstName($shippingData['firstName']);
        $order->setShippingLastName($shippingData['lastName']);
        $order->setShippingStreet($shippingData['address']);
        $order->setShippingCity($shippingData['city']);
        $order->setShippingPostalCode($shippingData['postalCode']);
        $order->setShippingCountry($shippingData['country'] ?? 'France');
        $order->setShippingPhone($shippingData['phone'] ?? '');

        // Calculate totals
        $subtotal = $cart->getTotal();
        $shippingCost = floatval($subtotal) >= 50 ? '0.00' : '5.99';

        $order->setSubtotal($subtotal);
        $order->setShippingCost($shippingCost);
        $order->setDiscount('0.00');
        $order->setTotal((string)(floatval($subtotal) + floatval($shippingCost)));

        // Create order items from cart items
        foreach ($cart->getItems() as $cartItem) {
            $variant = $cartItem->getVariant();
            $product = $variant->getProduct();

            $orderItem = new OrderItem();
            $orderItem->setOrderRef($order);
            $orderItem->setProduct($product);
            $orderItem->setVariant($variant);
            $orderItem->setProductName($product->getName());
            $orderItem->setProductSku($variant->getSku());
            $orderItem->setSize($variant->getSize());
            $orderItem->setColor($variant->getColor());
            $orderItem->setQuantity($cartItem->getQuantity());
            $orderItem->setUnitPrice($cartItem->getPriceAtAddition());
            $orderItem->setSubtotal((string)($cartItem->getQuantity() * floatval($cartItem->getPriceAtAddition())));

            $this->entityManager->persist($orderItem);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }

    public function updateOrderStatus(Order $order, string $status): void
    {
        $order->setStatus($status);
        $this->entityManager->flush();
    }

    public function updatePaymentStatus(Order $order, string $paymentStatus): void
    {
        $order->setPaymentStatus($paymentStatus);
        $this->entityManager->flush();
    }
}
