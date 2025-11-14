<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/account/orders')]
final class OrderController extends AbstractController
{
    #[Route('', name: 'app_orders')]
    public function index(OrderRepository $orderRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $orders = $orderRepository->findBy(
            ['user' => $this->getUser()],
            ['createdAt' => 'DESC']
        );

        return $this->render('front/account/orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/{id}', name: 'app_order_detail')]
    public function detail(int $id, OrderRepository $orderRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $order = $orderRepository->find($id);

        if (!$order || $order->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException('Commande non trouvée');
        }

        return $this->render('front/account/order-detail.html.twig', [
            'order' => $order,
        ]);
    }
}
