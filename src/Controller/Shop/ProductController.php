<?php

namespace App\Controller\Shop;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/shop/product', name: 'app_shop_product_')]
class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    #[Route('/show/{id}', name: 'show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('shop/product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
