<?php

namespace App\Controller;

use App\Service\CartService;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/checkout')]
final class CheckoutController extends AbstractController
{
    private CartService $cartService;
    private OrderService $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    #[Route('', name: 'app_checkout')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $cart = $this->cartService->getCart($this->getUser());

        if ($cart->getItems()->count() === 0) {
            $this->addFlash('error', 'Votre panier est vide');
            return $this->redirectToRoute('app_cart_index');
        }

        return $this->render('front/checkout/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/process', name: 'app_checkout_process', methods: ['POST'])]
    public function process(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $cart = $this->cartService->getCart($this->getUser());

        if ($cart->getItems()->count() === 0) {
            if ($request->headers->get('Content-Type') === 'application/json') {
                return $this->json(['success' => false, 'message' => 'Votre panier est vide'], 400);
            }
            $this->addFlash('error', 'Votre panier est vide');
            return $this->redirectToRoute('app_cart_index');
        }

        // Handle JSON request (from KKiaPay)
        if ($request->headers->get('Content-Type') === 'application/json') {
            $data = json_decode($request->getContent(), true);

            $shippingData = [
                'firstName' => $data['firstName'] ?? '',
                'lastName' => $data['lastName'] ?? '',
                'address' => $data['address'] ?? '',
                'city' => $data['city'] ?? '',
                'postalCode' => $data['postalCode'] ?? '',
                'country' => $data['country'] ?? 'France',
                'phone' => $data['phone'] ?? '',
            ];

            $transactionId = $data['transactionId'] ?? null;
            $paymentMethod = $data['paymentMethod'] ?? 'kkiapay';

            // Create order
            $order = $this->orderService->createOrderFromCart($this->getUser(), $cart, $shippingData);

            // Set payment info
            if ($transactionId) {
                $order->setTransactionId($transactionId);
                $order->setPaymentMethod($paymentMethod);

                // If Cash on Delivery, keep payment status as pending
                if ($paymentMethod === 'cash_on_delivery') {
                    $order->setPaymentStatus('pending');
                    $this->orderService->updateOrderStatus($order, 'pending');
                } else {
                    // KKiaPay or other online payment
                    $order->setPaymentStatus('paid');
                    $this->orderService->updateOrderStatus($order, 'processing');
                }
            }

            // Clear cart after order
            $this->cartService->clearCart($this->getUser());

            return $this->json([
                'success' => true,
                'orderNumber' => $order->getOrderNumber()
            ]);
        }

        // Handle traditional form submission
        $shippingData = [
            'firstName' => $request->request->get('firstName'),
            'lastName' => $request->request->get('lastName'),
            'address' => $request->request->get('address'),
            'city' => $request->request->get('city'),
            'postalCode' => $request->request->get('postalCode'),
            'country' => $request->request->get('country', 'France'),
            'phone' => $request->request->get('phone'),
        ];

        // Create order
        $order = $this->orderService->createOrderFromCart($this->getUser(), $cart, $shippingData);

        // Clear cart after order
        $this->cartService->clearCart($this->getUser());

        $this->addFlash('success', 'Votre commande a été créée avec succès!');

        return $this->redirectToRoute('app_order_confirmation', ['orderNumber' => $order->getOrderNumber()]);
    }

    #[Route('/confirmation/{orderNumber}', name: 'app_order_confirmation')]
    public function confirmation(string $orderNumber): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('front/checkout/confirmation.html.twig', [
            'orderNumber' => $orderNumber,
        ]);
    }
}
