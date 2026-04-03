<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'name' => 'Balicka'
        ]);
    }

    #[Route('/products', name: 'app_products')]
    public function products(): Response
    {
        $products = [
            ['id' => 1, 'name' => 'Laptop', 'price' => 3000],
            ['id' => 2, 'name' => 'Monitor', 'price' => 1200],
            ['id' => 3, 'name' => 'Myszka', 'price' => 150],
        ];

        return $this->render('test/products.html.twig', [
            'products' => $products
        ]);
    }
}
