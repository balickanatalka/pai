<?php

namespace App\Twig;

use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ShopNavigationExtension extends AbstractExtension
{
    public function __construct(
        private readonly BrandRepository $brandRepository,
        private readonly CategoryRepository $categoryRepository,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('shop_brands', [$this, 'getBrands']),
            new TwigFunction('shop_categories', [$this, 'getCategories']),
        ];
    }

    public function getBrands(): array
    {
        return $this->brandRepository->findUsedBrands();
    }

    public function getCategories(): array
    {
        return $this->categoryRepository->findUsedCategories();
    }
}
