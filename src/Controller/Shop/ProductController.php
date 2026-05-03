<?php

namespace App\Controller\Shop;

use App\Entity\Product;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/shop/product', name: 'app_shop_product_')]
class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly BrandRepository $brandRepository,
        private readonly CategoryRepository $categoryRepository,
    ) {
    }

    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request): Response
    {
        $query = trim((string) $request->query->get('q', ''));
        $brand = trim((string) $request->query->get('brand', ''));
        $category = trim((string) $request->query->get('category', ''));

        $products = $this->productRepository->searchWithFilters(
            $query !== '' ? $query : null,
            $brand !== '' ? $brand : null,
            $category !== '' ? $category : null
        );

        return $this->render('shop/product/search.html.twig', [
            'products' => $products,
            'query' => $query,
            'brand' => $brand,
            'category' => $category,
            'brands' => $this->brandRepository->findBy([], ['name' => 'ASC']),
            'categories' => $this->categoryRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Product $product): Response
    {
        return $this->render('shop/product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
