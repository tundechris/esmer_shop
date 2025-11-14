<?php

namespace App\Controller\Admin;

use App\Repository\OrderRepository;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/orders')]
final class OrderAdminController extends AbstractController
{
    private OrderRepository $orderRepository;
    private OrderService $orderService;

    public function __construct(OrderRepository $orderRepository, OrderService $orderService)
    {
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
    }

    #[Route('', name: 'admin_orders')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $orders = $this->orderRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/order/list.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/{id}', name: 'admin_order_detail')]
    public function detail(int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $order = $this->orderRepository->find($id);

        if (!$order) {
            throw $this->createNotFoundException('Commande non trouvée');
        }

        return $this->render('admin/order/detail.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}/status', name: 'admin_order_update_status', methods: ['POST'])]
    public function updateStatus(int $id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $order = $this->orderRepository->find($id);

        if (!$order) {
            throw $this->createNotFoundException('Commande non trouvée');
        }

        $newStatus = $request->request->get('status');
        if (!in_array($newStatus, ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])) {
            $this->addFlash('error', 'Statut invalide');
            return $this->redirectToRoute('admin_order_detail', ['id' => $id]);
        }

        $this->orderService->updateOrderStatus($order, $newStatus);
        $this->addFlash('success', 'Statut de la commande mis à jour');

        return $this->redirectToRoute('admin_order_detail', ['id' => $id]);
    }

    #[Route('/{id}/payment-status', name: 'admin_order_update_payment_status', methods: ['POST'])]
    public function updatePaymentStatus(int $id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $order = $this->orderRepository->find($id);

        if (!$order) {
            throw $this->createNotFoundException('Commande non trouvée');
        }

        $newPaymentStatus = $request->request->get('paymentStatus');
        if (!in_array($newPaymentStatus, ['pending', 'paid', 'failed', 'refunded'])) {
            $this->addFlash('error', 'Statut de paiement invalide');
            return $this->redirectToRoute('admin_order_detail', ['id' => $id]);
        }

        $this->orderService->updatePaymentStatus($order, $newPaymentStatus);
        $this->addFlash('success', 'Statut de paiement mis à jour');

        return $this->redirectToRoute('admin_order_detail', ['id' => $id]);
    }
}
