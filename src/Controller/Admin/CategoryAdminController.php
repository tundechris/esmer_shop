<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/categories')]
final class CategoryAdminController extends AbstractController
{
    private CategoryRepository $categoryRepository;
    private EntityManagerInterface $entityManager;
    private SluggerInterface $slugger;

    public function __construct(
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
    }

    #[Route('', name: 'admin_categories_list')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $categories = $this->categoryRepository->findBy([], ['name' => 'ASC']);

        return $this->render('admin/category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/new', name: 'admin_category_new')]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Generate slug from name
            $slug = $this->slugger->slug($category->getName())->lower();
            $category->setSlug($slug);

            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $this->addFlash('success', 'Catégorie créée avec succès');
            return $this->redirectToRoute('admin_categories_list');
        }

        return $this->render('admin/category/form.html.twig', [
            'form' => $form,
            'category' => $category,
            'isEdit' => false,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_category_edit')]
    public function edit(int $id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $category = $this->categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Update slug if name changed
            $slug = $this->slugger->slug($category->getName())->lower();
            $category->setSlug($slug);

            $this->entityManager->flush();

            $this->addFlash('success', 'Catégorie modifiée avec succès');
            return $this->redirectToRoute('admin_categories_list');
        }

        return $this->render('admin/category/form.html.twig', [
            'form' => $form,
            'category' => $category,
            'isEdit' => true,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_category_delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $category = $this->categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        // Check if category has products
        if ($category->getProducts()->count() > 0) {
            $this->addFlash('error', 'Impossible de supprimer cette catégorie car elle contient des produits');
            return $this->redirectToRoute('admin_categories_list');
        }

        $this->entityManager->remove($category);
        $this->entityManager->flush();

        $this->addFlash('success', 'Catégorie supprimée avec succès');
        return $this->redirectToRoute('admin_categories_list');
    }
}
