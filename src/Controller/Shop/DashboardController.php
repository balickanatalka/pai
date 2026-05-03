<?php

namespace App\Controller\Shop;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/shop', name: 'app_shop_')]
class DashboardController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    #[Route('/', name: 'dashboard', methods: ['GET'])]
    public function index(): Response
    {
        $products = $this->productRepository->findAll();

        return $this->render('shop/dashboard/index.html.twig', [
            'products' => $products,
        ]);
    }
}
