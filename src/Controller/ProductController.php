<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{

    #[Route('/product/list', name: 'app_product_index')]
    public function index(): Response
    {
        $products = [
            ['id' => 1, 'name' => 'Laptop', 'price' => 3000],
            ['id' => 2, 'name' => 'Monitor', 'price' => 1200],
            ['id' => 3, 'name' => 'Myszka', 'price' => 150],
        ];

        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }
}
