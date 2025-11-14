<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart')]
final class CartController extends AbstractController
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    #[Route('', name: 'app_cart_index')]
    public function index(): Response
    {
        $cart = $this->cartService->getCart($this->getUser());

        return $this->render('front/cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/add', name: 'app_cart_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $variantId = $data['variantId'] ?? null;
        $quantity = $data['quantity'] ?? 1;

        if (!$variantId) {
            return $this->json([
                'success' => false,
                'message' => 'ID de variant manquant'
            ], 400);
        }

        $success = $this->cartService->addItem($this->getUser(), $variantId, $quantity);

        if ($success) {
            return $this->json([
                'success' => true,
                'message' => 'Produit ajouté au panier',
                'cartItemsCount' => $this->cartService->getCartItemsCount($this->getUser()),
                'cartTotal' => $this->cartService->getCartTotal($this->getUser()),
            ]);
        }

        return $this->json([
            'success' => false,
            'message' => 'Impossible d\'ajouter le produit (stock insuffisant ou produit introuvable)'
        ], 400);
    }

    #[Route('/update/{itemId}', name: 'app_cart_update', methods: ['POST'])]
    public function update(int $itemId, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $quantity = $data['quantity'] ?? 1;

        if ($quantity < 1) {
            return $this->json([
                'success' => false,
                'message' => 'Quantité invalide'
            ], 400);
        }

        $success = $this->cartService->updateItemQuantity($this->getUser(), $itemId, $quantity);

        if ($success) {
            return $this->json([
                'success' => true,
                'message' => 'Quantité mise à jour',
                'cartTotal' => $this->cartService->getCartTotal($this->getUser()),
            ]);
        }

        return $this->json([
            'success' => false,
            'message' => 'Impossible de mettre à jour (stock insuffisant)'
        ], 400);
    }

    #[Route('/remove/{itemId}', name: 'app_cart_remove', methods: ['POST'])]
    public function remove(int $itemId): JsonResponse
    {
        $success = $this->cartService->removeItem($this->getUser(), $itemId);

        if ($success) {
            return $this->json([
                'success' => true,
                'message' => 'Produit retiré du panier',
                'cartItemsCount' => $this->cartService->getCartItemsCount($this->getUser()),
                'cartTotal' => $this->cartService->getCartTotal($this->getUser()),
            ]);
        }

        return $this->json([
            'success' => false,
            'message' => 'Produit non trouvé'
        ], 404);
    }

    #[Route('/clear', name: 'app_cart_clear', methods: ['POST'])]
    public function clear(): JsonResponse
    {
        $this->cartService->clearCart($this->getUser());

        return $this->json([
            'success' => true,
            'message' => 'Panier vidé',
        ]);
    }
}
