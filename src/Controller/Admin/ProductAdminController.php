<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/products')]
final class ProductAdminController extends AbstractController
{
    #[Route('', name: 'admin_products_list')]
    public function list(ProductRepository $productRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $products = $productRepository->findAll();

        return $this->render('admin/product/list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/new', name: 'admin_products_new')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository,
        BrandRepository $brandRepository,
        SluggerInterface $slugger
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($request->isMethod('POST')) {
            $product = new Product();

            $product->setName($request->request->get('name'));
            $product->setSlug(strtolower($slugger->slug($request->request->get('name'))));
            $product->setDescription($request->request->get('description'));
            $product->setPrice($request->request->get('price'));

            if ($request->request->get('discountPrice')) {
                $product->setDiscountPrice($request->request->get('discountPrice'));
            }

            $category = $categoryRepository->find($request->request->get('category'));
            $brand = $brandRepository->find($request->request->get('brand'));

            $product->setCategory($category);
            $product->setBrand($brand);
            $product->setIsFeatured($request->request->get('isFeatured') === 'on');
            $product->setIsNewArrival($request->request->get('isNewArrival') === 'on');
            $product->setImages([$request->request->get('image')]);

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Produit créé avec succès');
            return $this->redirectToRoute('admin_products_list');
        }

        return $this->render('admin/product/form.html.twig', [
            'product' => null,
            'categories' => $categoryRepository->findAll(),
            'brands' => $brandRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_products_edit')]
    public function edit(
        int $id,
        Request $request,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository,
        BrandRepository $brandRepository,
        SluggerInterface $slugger
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        if ($request->isMethod('POST')) {
            $product->setName($request->request->get('name'));
            $product->setSlug(strtolower($slugger->slug($request->request->get('name'))));
            $product->setDescription($request->request->get('description'));
            $product->setPrice($request->request->get('price'));

            if ($request->request->get('discountPrice')) {
                $product->setDiscountPrice($request->request->get('discountPrice'));
            } else {
                $product->setDiscountPrice(null);
            }

            $category = $categoryRepository->find($request->request->get('category'));
            $brand = $brandRepository->find($request->request->get('brand'));

            $product->setCategory($category);
            $product->setBrand($brand);
            $product->setIsFeatured($request->request->get('isFeatured') === 'on');
            $product->setIsNewArrival($request->request->get('isNewArrival') === 'on');

            if ($request->request->get('image')) {
                $product->setImages([$request->request->get('image')]);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Produit modifié avec succès');
            return $this->redirectToRoute('admin_products_list');
        }

        return $this->render('admin/product/form.html.twig', [
            'product' => $product,
            'categories' => $categoryRepository->findAll(),
            'brands' => $brandRepository->findAll(),
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_products_delete', methods: ['POST'])]
    public function delete(
        int $id,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        $entityManager->remove($product);
        $entityManager->flush();

        $this->addFlash('success', 'Produit supprimé avec succès');
        return $this->redirectToRoute('admin_products_list');
    }
}
