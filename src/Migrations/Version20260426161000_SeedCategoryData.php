<?php

declare(strict_types=1);

namespace App\Migrations;

use App\Entity\Category;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

final class Version20260426161000_SeedCategoryData extends AbstractMigration
{
    public function __construct(
        Connection $connection,
        LoggerInterface $logger,
        private EntityManagerInterface $em,
    ) {
        parent::__construct($connection, $logger);
    }

    public function getDescription(): string
    {
        return 'Seed initial Category data using Doctrine entities.';
    }

    public function up(Schema $schema): void
    {
        $categories = [
            'Smartphones',
            'Laptops',
            'Tablets',
            'Monitors',
            'Headphones',
            'Keyboards',
            'Mice',
            'Printers',
            'Cameras',
            'Accessories',
        ];

        $categoryRepository = $this->em->getRepository(Category::class);

        foreach ($categories as $categoryName) {
            $existing = $categoryRepository->findOneBy([
                'name' => $categoryName,
            ]);

            if ($existing !== null) {
                continue;
            }

            $category = new Category();
            $category->setName($categoryName);

            $this->em->persist($category);
        }

        $this->em->flush();
    }

    public function down(Schema $schema): void
    {
        $categories = [
            'Smartphones',
            'Laptops',
            'Tablets',
            'Monitors',
            'Headphones',
            'Keyboards',
            'Mice',
            'Printers',
            'Cameras',
            'Accessories',
        ];

        $categoryRepository = $this->em->getRepository(Category::class);

        foreach ($categories as $categoryName) {
            $category = $categoryRepository->findOneBy([
                'name' => $categoryName,
            ]);

            if ($category === null) {
                continue;
            }

            $this->em->remove($category);
        }

        $this->em->flush();
    }
}
