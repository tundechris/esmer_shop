<?php

namespace App\Controller\Admin;

use App\Entity\ProductVariant;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/products/{productId}/variants')]
final class ProductVariantController extends AbstractController
{
    private ProductRepository $productRepository;
    private ProductVariantRepository $variantRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ProductRepository $productRepository,
        ProductVariantRepository $variantRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->productRepository = $productRepository;
        $this->variantRepository = $variantRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('', name: 'admin_product_variants')]
    public function index(int $productId): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        return $this->render('admin/product/variants.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/add', name: 'admin_product_variant_add', methods: ['POST'])]
    public function add(int $productId, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        $variant = new ProductVariant();
        $variant->setProduct($product);
        $variant->setSize($request->request->get('size'));
        $variant->setColor($request->request->get('color'));
        $variant->setColorCode($request->request->get('colorCode'));
        $variant->setStock((int)$request->request->get('stock'));

        if ($request->request->get('sku')) {
            $variant->setSku($request->request->get('sku'));
        }

        $this->entityManager->persist($variant);
        $this->entityManager->flush();

        $this->addFlash('success', 'Variant ajouté avec succès');
        return $this->redirectToRoute('admin_product_variants', ['productId' => $productId]);
    }

    #[Route('/{id}/update', name: 'admin_product_variant_update', methods: ['POST'])]
    public function update(int $productId, int $id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $variant = $this->variantRepository->find($id);
        if (!$variant) {
            throw $this->createNotFoundException('Variant non trouvé');
        }

        $variant->setSize($request->request->get('size'));
        $variant->setColor($request->request->get('color'));
        $variant->setColorCode($request->request->get('colorCode'));
        $variant->setStock((int)$request->request->get('stock'));

        if ($request->request->get('sku')) {
            $variant->setSku($request->request->get('sku'));
        }

        $this->entityManager->flush();

        $this->addFlash('success', 'Variant modifié avec succès');
        return $this->redirectToRoute('admin_product_variants', ['productId' => $productId]);
    }

    #[Route('/{id}/delete', name: 'admin_product_variant_delete', methods: ['POST'])]
    public function delete(int $productId, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $variant = $this->variantRepository->find($id);
        if (!$variant) {
            throw $this->createNotFoundException('Variant non trouvé');
        }

        $this->entityManager->remove($variant);
        $this->entityManager->flush();

        $this->addFlash('success', 'Variant supprimé avec succès');
        return $this->redirectToRoute('admin_product_variants', ['productId' => $productId]);
    }
}
