<?php

namespace App\Controller\Shop;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/shop', name: 'app_shop_')]
class DashboardController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    #[Route('/', name: 'dashboard', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $query = $request->query->get('q');

        if ($query) {
            $products = $this->productRepository
                ->createQueryBuilder('p')
                ->where('LOWER(p.name) LIKE LOWER(:query)')
                ->setParameter('query', '%' . $query . '%')
                ->orderBy('p.id', 'ASC')
                ->getQuery()
                ->getResult();
        } else {
            $products = $this->productRepository->findBy([], [
                'id' => 'ASC',
            ]);
        }

        return $this->render('shop/dashboard/index.html.twig', [
            'products' => $products,
        ]);
    }
}
