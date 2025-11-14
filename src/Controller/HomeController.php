<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response
    {
        // Récupérer les produits featured pour la page d'accueil
        $featuredProducts = $productRepository->findBy(['isFeatured' => true], null, 8);
        $newProducts = $productRepository->findBy(['isNewArrival' => true], null, 4);

        return $this->render('home/index.html.twig', [
            'featuredProducts' => $featuredProducts,
            'newProducts' => $newProducts,
        ]);
    }

    #[Route('/products', name: 'app_products')]
    public function products(
        Request $request,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        BrandRepository $brandRepository
    ): Response {
        $category = $request->query->get('category');
        $brand = $request->query->get('brand');
        $search = $request->query->get('search');
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 12; // Nombre de produits par page

        // Récupérer tous les produits avec filtres
        $queryBuilder = $productRepository->createQueryBuilder('p');

        if ($category) {
            $queryBuilder->join('p.category', 'c')
                ->andWhere('c.slug = :category')
                ->setParameter('category', $category);
        }

        if ($brand) {
            $queryBuilder->join('p.brand', 'b')
                ->andWhere('b.slug = :brand')
                ->setParameter('brand', $brand);
        }

        if ($search) {
            $queryBuilder->andWhere('p.name LIKE :search OR p.description LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        // Compter le nombre total de résultats
        $totalQuery = clone $queryBuilder;
        $totalItems = count($totalQuery->getQuery()->getResult());
        $totalPages = (int) ceil($totalItems / $limit);

        // Limiter les résultats pour la pagination
        $products = $queryBuilder
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return $this->render('front/product/list.html.twig', [
            'products' => $products,
            'categories' => $categoryRepository->findAll(),
            'brands' => $brandRepository->findAll(),
            'currentCategory' => $category,
            'currentBrand' => $brand,
            'currentSearch' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
        ]);
    }

    #[Route('/product/{slug}', name: 'app_product_detail')]
    public function productDetail(string $slug, ProductRepository $productRepository): Response
    {
        // Version: 2025-FIXED
        $product = $productRepository->findOneBy(['slug' => $slug]);

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        // Récupérer les produits similaires (même catégorie)
        $similarProducts = $productRepository->createQueryBuilder('p')
            ->where('p.category = :category')
            ->andWhere('p.id != :productId')
            ->setParameter('category', $product->getCategory())
            ->setParameter('productId', $product->getId())
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();

        // Normaliser les variants pour JavaScript
        $variantsData = [];
        foreach ($product->getVariants() as $variant) {
            $variantsData[] = [
                'id' => $variant->getId(),
                'size' => $variant->getSize(),
                'color' => $variant->getColor(),
                'colorCode' => $variant->getColorCode(),
                'stock' => $variant->getStock(),
            ];
        }

        return $this->render('front/product/detail.html.twig', [
            'product' => $product,
            'similarProducts' => $similarProducts,
            'variantsData' => json_encode($variantsData),
        ]);
    }

    #[Route('/account', name: 'app_account')]
    public function account(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('front/account/index.html.twig');
    }
}
