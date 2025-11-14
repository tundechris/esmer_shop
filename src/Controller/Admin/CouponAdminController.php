<?php

namespace App\Controller\Admin;

use App\Entity\Coupon;
use App\Form\CouponType;
use App\Repository\CouponRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/coupons')]
final class CouponAdminController extends AbstractController
{
    private CouponRepository $couponRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        CouponRepository $couponRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->couponRepository = $couponRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('', name: 'admin_coupons_list')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $coupons = $this->couponRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/coupon/list.html.twig', [
            'coupons' => $coupons,
        ]);
    }

    #[Route('/new', name: 'admin_coupon_new')]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $coupon = new Coupon();
        $form = $this->createForm(CouponType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($coupon);
            $this->entityManager->flush();

            $this->addFlash('success', 'Coupon créé avec succès');
            return $this->redirectToRoute('admin_coupons_list');
        }

        return $this->render('admin/coupon/form.html.twig', [
            'form' => $form,
            'coupon' => $coupon,
            'isEdit' => false,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_coupon_edit')]
    public function edit(int $id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $coupon = $this->couponRepository->find($id);

        if (!$coupon) {
            throw $this->createNotFoundException('Coupon non trouvé');
        }

        $form = $this->createForm(CouponType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Coupon modifié avec succès');
            return $this->redirectToRoute('admin_coupons_list');
        }

        return $this->render('admin/coupon/form.html.twig', [
            'form' => $form,
            'coupon' => $coupon,
            'isEdit' => true,
        ]);
    }

    #[Route('/{id}/toggle', name: 'admin_coupon_toggle', methods: ['POST'])]
    public function toggle(int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $coupon = $this->couponRepository->find($id);

        if (!$coupon) {
            throw $this->createNotFoundException('Coupon non trouvé');
        }

        $coupon->setIsActive(!$coupon->isActive());
        $this->entityManager->flush();

        $this->addFlash('success', $coupon->isActive() ? 'Coupon activé' : 'Coupon désactivé');
        return $this->redirectToRoute('admin_coupons_list');
    }

    #[Route('/{id}/delete', name: 'admin_coupon_delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $coupon = $this->couponRepository->find($id);

        if (!$coupon) {
            throw $this->createNotFoundException('Coupon non trouvé');
        }

        $this->entityManager->remove($coupon);
        $this->entityManager->flush();

        $this->addFlash('success', 'Coupon supprimé avec succès');
        return $this->redirectToRoute('admin_coupons_list');
    }
}
