<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\WishlistService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/wishlist')]
final class WishlistController extends AbstractController
{
    private WishlistService $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    #[Route('', name: 'app_wishlist')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('front/wishlist/index.html.twig', [
            'wishlist' => $this->getUser()->getWishlist(),
        ]);
    }

    #[Route('/toggle/{id}', name: 'app_wishlist_toggle', methods: ['POST'])]
    public function toggle(int $id, ProductRepository $productRepository): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $product = $productRepository->find($id);
        if (!$product) {
            return $this->json(['success' => false, 'message' => 'Produit non trouvé'], 404);
        }

        $user = $this->getUser();
        $isInWishlist = $this->wishlistService->isInWishlist($user, $product);

        if ($isInWishlist) {
            $this->wishlistService->removeFromWishlist($user, $product);
            $message = 'Produit retiré de la wishlist';
            $added = false;
        } else {
            $this->wishlistService->addToWishlist($user, $product);
            $message = 'Produit ajouté à la wishlist';
            $added = true;
        }

        return $this->json([
            'success' => true,
            'message' => $message,
            'added' => $added,
            'count' => $this->wishlistService->getWishlistCount($user),
        ]);
    }
}
