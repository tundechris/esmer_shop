<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/clients')]
final class ClientAdminController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('', name: 'admin_clients_list')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Get all users ordered by registration date (newest first)
        $clients = $this->userRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/client/list.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/{id}', name: 'admin_client_detail')]
    public function detail(int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $client = $this->userRepository->find($id);

        if (!$client) {
            throw $this->createNotFoundException('Client non trouvé');
        }

        // Calculate total spent
        $totalSpent = 0;
        foreach ($client->getOrders() as $order) {
            if ($order->getPaymentStatus() === 'paid') {
                $totalSpent += floatval($order->getTotal());
            }
        }

        return $this->render('admin/client/detail.html.twig', [
            'client' => $client,
            'totalSpent' => $totalSpent,
        ]);
    }
}
