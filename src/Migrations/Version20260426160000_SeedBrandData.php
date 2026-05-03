<?php

declare(strict_types=1);

namespace App\Migrations;

use App\Entity\Brand;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

final class Version20260426160000_SeedBrandData extends AbstractMigration
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
        return 'Seed initial Brand data using Doctrine entities.';
    }

    public function up(Schema $schema): void
    {
        $brandNames = [
            'Apple',
            'Samsung',
            'Sony',
            'LG',
            'Lenovo',
            'Dell',
            'HP',
            'Asus',
            'Acer',
            'Xiaomi',
        ];

        $brandRepository = $this->em->getRepository(Brand::class);

        foreach ($brandNames as $brandName) {
            $existingBrand = $brandRepository->findOneBy([
                'name' => $brandName,
            ]);

            if ($existingBrand !== null) {
                continue;
            }

            $brand = new Brand();
            $brand->setName($brandName);

            $this->em->persist($brand);
        }

        $this->em->flush();
    }

    public function down(Schema $schema): void
    {
        $brandNames = [
            'Apple',
            'Samsung',
            'Sony',
            'LG',
            'Lenovo',
            'Dell',
            'HP',
            'Asus',
            'Acer',
            'Xiaomi',
        ];

        $brandRepository = $this->em->getRepository(Brand::class);

        foreach ($brandNames as $brandName) {
            $brand = $brandRepository->findOneBy([
                'name' => $brandName,
            ]);

            if ($brand === null) {
                continue;
            }

            $this->em->remove($brand);
        }

        $this->em->flush();
    }
}
