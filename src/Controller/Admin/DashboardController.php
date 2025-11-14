<?php

namespace App\Controller\Admin;

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class DashboardController extends AbstractController
{
    private OrderRepository $orderRepository;
    private ProductRepository $productRepository;
    private UserRepository $userRepository;

    public function __construct(
        OrderRepository $orderRepository,
        ProductRepository $productRepository,
        UserRepository $userRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
    }

    #[Route('/', name: 'admin_dashboard')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Get current month start date
        $currentMonthStart = new \DateTimeImmutable('first day of this month 00:00:00');
        $lastMonthStart = new \DateTimeImmutable('first day of last month 00:00:00');
        $lastMonthEnd = new \DateTimeImmutable('last day of last month 23:59:59');

        // Get all orders
        $allOrders = $this->orderRepository->findAll();

        // Calculate current month sales
        $currentMonthSales = 0;
        $currentMonthOrdersCount = 0;
        foreach ($allOrders as $order) {
            if ($order->getCreatedAt() >= $currentMonthStart && $order->getPaymentStatus() === 'paid') {
                $currentMonthSales += floatval($order->getTotal());
                $currentMonthOrdersCount++;
            }
        }

        // Calculate last month sales for comparison
        $lastMonthSales = 0;
        $lastMonthOrdersCount = 0;
        foreach ($allOrders as $order) {
            if ($order->getCreatedAt() >= $lastMonthStart && $order->getCreatedAt() <= $lastMonthEnd && $order->getPaymentStatus() === 'paid') {
                $lastMonthSales += floatval($order->getTotal());
                $lastMonthOrdersCount++;
            }
        }

        // Calculate percentage changes
        $salesChange = $lastMonthSales > 0 ? (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100 : 0;
        $ordersChange = $lastMonthOrdersCount > 0 ? (($currentMonthOrdersCount - $lastMonthOrdersCount) / $lastMonthOrdersCount) * 100 : 0;

        // Get total products and clients
        $totalProducts = $this->productRepository->count([]);
        $totalClients = $this->userRepository->count([]);

        // Get recent orders (last 10)
        $recentOrders = $this->orderRepository->findBy([], ['createdAt' => 'DESC'], 10);

        return $this->render('admin/dashboard/index.html.twig', [
            'currentMonthSales' => $currentMonthSales,
            'salesChange' => $salesChange,
            'currentMonthOrdersCount' => $currentMonthOrdersCount,
            'ordersChange' => $ordersChange,
            'totalProducts' => $totalProducts,
            'totalClients' => $totalClients,
            'recentOrders' => $recentOrders,
        ]);
    }
}
