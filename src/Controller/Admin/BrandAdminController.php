<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/brands')]
final class BrandAdminController extends AbstractController
{
    private BrandRepository $brandRepository;
    private EntityManagerInterface $entityManager;
    private SluggerInterface $slugger;

    public function __construct(
        BrandRepository $brandRepository,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ) {
        $this->brandRepository = $brandRepository;
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
    }

    #[Route('', name: 'admin_brands_list')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $brands = $this->brandRepository->findBy([], ['name' => 'ASC']);

        return $this->render('admin/brand/list.html.twig', [
            'brands' => $brands,
        ]);
    }

    #[Route('/new', name: 'admin_brand_new')]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $brand = new Brand();
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Generate slug from name
            $slug = $this->slugger->slug($brand->getName())->lower();
            $brand->setSlug($slug);

            $this->entityManager->persist($brand);
            $this->entityManager->flush();

            $this->addFlash('success', 'Marque créée avec succès');
            return $this->redirectToRoute('admin_brands_list');
        }

        return $this->render('admin/brand/form.html.twig', [
            'form' => $form,
            'brand' => $brand,
            'isEdit' => false,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_brand_edit')]
    public function edit(int $id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $brand = $this->brandRepository->find($id);

        if (!$brand) {
            throw $this->createNotFoundException('Marque non trouvée');
        }

        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Update slug if name changed
            $slug = $this->slugger->slug($brand->getName())->lower();
            $brand->setSlug($slug);

            $this->entityManager->flush();

            $this->addFlash('success', 'Marque modifiée avec succès');
            return $this->redirectToRoute('admin_brands_list');
        }

        return $this->render('admin/brand/form.html.twig', [
            'form' => $form,
            'brand' => $brand,
            'isEdit' => true,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_brand_delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $brand = $this->brandRepository->find($id);

        if (!$brand) {
            throw $this->createNotFoundException('Marque non trouvée');
        }

        // Check if brand has products
        if ($brand->getProducts()->count() > 0) {
            $this->addFlash('error', 'Impossible de supprimer cette marque car elle contient des produits');
            return $this->redirectToRoute('admin_brands_list');
        }

        $this->entityManager->remove($brand);
        $this->entityManager->flush();

        $this->addFlash('success', 'Marque supprimée avec succès');
        return $this->redirectToRoute('admin_brands_list');
    }
}
