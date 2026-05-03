<?php

declare(strict_types=1);

namespace App\Migrations;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

final class Version20260426162000_SeedProductData extends AbstractMigration
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
        return 'Seed 100 realistic products using Doctrine entities.';
    }

    public function up(Schema $schema): void
    {
        $brandRepository = $this->em->getRepository(Brand::class);
        $categoryRepository = $this->em->getRepository(Category::class);
        $productRepository = $this->em->getRepository(Product::class);

        $products = [
            ['iPhone 15', 'Apple', 'Smartphones', 'A3090', '899.99', 25],
            ['iPhone 15 Pro', 'Apple', 'Smartphones', 'A3102', '1199.99', 18],
            ['iPhone 14', 'Apple', 'Smartphones', 'A2882', '749.99', 30],
            ['Galaxy S24', 'Samsung', 'Smartphones', 'SM-S921B', '849.99', 22],
            ['Galaxy S24 Ultra', 'Samsung', 'Smartphones', 'SM-S928B', '1299.99', 15],
            ['Galaxy A55', 'Samsung', 'Smartphones', 'SM-A556B', '449.99', 45],
            ['Xiaomi 14', 'Xiaomi', 'Smartphones', 'XIAOMI-14', '799.99', 28],
            ['Xiaomi Redmi Note 13 Pro', 'Xiaomi', 'Smartphones', 'RN13-PRO', '349.99', 60],
            ['Sony Xperia 1 V', 'Sony', 'Smartphones', 'XQ-DQ54', '999.99', 12],
            ['Sony Xperia 10 V', 'Sony', 'Smartphones', 'XQ-DC54', '399.99', 20],

            ['MacBook Air 13 M2', 'Apple', 'Laptops', 'MBA13-M2', '1099.99', 16],
            ['MacBook Air 15 M2', 'Apple', 'Laptops', 'MBA15-M2', '1299.99', 11],
            ['MacBook Pro 14 M3', 'Apple', 'Laptops', 'MBP14-M3', '1999.99', 9],
            ['Lenovo ThinkPad X1 Carbon', 'Lenovo', 'Laptops', 'X1-CARBON-G11', '1599.99', 14],
            ['Lenovo IdeaPad Slim 5', 'Lenovo', 'Laptops', 'IPS5-14', '699.99', 32],
            ['Dell XPS 13', 'Dell', 'Laptops', 'XPS13-9340', '1399.99', 13],
            ['Dell Inspiron 15', 'Dell', 'Laptops', 'INS15-3530', '599.99', 27],
            ['HP Spectre x360', 'HP', 'Laptops', 'SPX360-14', '1499.99', 10],
            ['HP Pavilion 15', 'HP', 'Laptops', 'PAV15-EG', '649.99', 35],
            ['Asus ZenBook 14', 'Asus', 'Laptops', 'UX3405', '999.99', 19],

            ['iPad 10th Gen', 'Apple', 'Tablets', 'IPAD10', '499.99', 26],
            ['iPad Air 5', 'Apple', 'Tablets', 'IPADAIR5', '699.99', 17],
            ['iPad Pro 11', 'Apple', 'Tablets', 'IPADPRO11', '999.99', 12],
            ['Galaxy Tab S9', 'Samsung', 'Tablets', 'TAB-S9', '799.99', 20],
            ['Galaxy Tab A9+', 'Samsung', 'Tablets', 'TAB-A9PLUS', '299.99', 40],
            ['Lenovo Tab P12', 'Lenovo', 'Tablets', 'TAB-P12', '399.99', 33],
            ['Lenovo Tab M10', 'Lenovo', 'Tablets', 'TAB-M10', '199.99', 50],
            ['Xiaomi Pad 6', 'Xiaomi', 'Tablets', 'PAD6', '349.99', 31],
            ['Xiaomi Redmi Pad SE', 'Xiaomi', 'Tablets', 'PAD-SE', '229.99', 46],
            ['Acer Iconia Tab', 'Acer', 'Tablets', 'ICONIA-TAB', '179.99', 25],

            ['LG UltraGear 27GP850', 'LG', 'Monitors', '27GP850', '349.99', 18],
            ['LG UltraFine 27UN880', 'LG', 'Monitors', '27UN880', '499.99', 14],
            ['Samsung Odyssey G5', 'Samsung', 'Monitors', 'ODYSSEY-G5', '299.99', 22],
            ['Samsung Odyssey G7', 'Samsung', 'Monitors', 'ODYSSEY-G7', '599.99', 11],
            ['Dell UltraSharp U2723QE', 'Dell', 'Monitors', 'U2723QE', '649.99', 9],
            ['Dell S2721QS', 'Dell', 'Monitors', 'S2721QS', '329.99', 20],
            ['HP M27fw', 'HP', 'Monitors', 'M27FW', '199.99', 37],
            ['Asus ProArt PA278QV', 'Asus', 'Monitors', 'PA278QV', '299.99', 16],
            ['Acer Nitro VG271U', 'Acer', 'Monitors', 'VG271U', '269.99', 24],
            ['Lenovo ThinkVision P27h', 'Lenovo', 'Monitors', 'P27H-30', '399.99', 13],

            ['Sony WH-1000XM5', 'Sony', 'Headphones', 'WH1000XM5', '399.99', 30],
            ['Sony WH-1000XM4', 'Sony', 'Headphones', 'WH1000XM4', '299.99', 38],
            ['Sony WF-1000XM5', 'Sony', 'Headphones', 'WF1000XM5', '279.99', 35],
            ['Apple AirPods Pro 2', 'Apple', 'Headphones', 'AIRPODS-PRO2', '249.99', 50],
            ['Apple AirPods 3', 'Apple', 'Headphones', 'AIRPODS3', '179.99', 42],
            ['Samsung Galaxy Buds2 Pro', 'Samsung', 'Headphones', 'BUDS2-PRO', '199.99', 44],
            ['Samsung Galaxy Buds FE', 'Samsung', 'Headphones', 'BUDS-FE', '99.99', 70],
            ['Xiaomi Redmi Buds 5 Pro', 'Xiaomi', 'Headphones', 'BUDS5-PRO', '79.99', 80],
            ['HP HyperX Cloud II', 'HP', 'Headphones', 'CLOUD-II', '89.99', 45],
            ['Asus ROG Delta S', 'Asus', 'Headphones', 'ROG-DELTA-S', '149.99', 21],

            ['Apple Magic Keyboard', 'Apple', 'Keyboards', 'MAGIC-KEYBOARD', '119.99', 34],
            ['Apple Magic Keyboard Touch ID', 'Apple', 'Keyboards', 'MAGIC-KB-TID', '179.99', 20],
            ['Samsung Smart Keyboard Trio 500', 'Samsung', 'Keyboards', 'TRIO500', '59.99', 38],
            ['Lenovo Preferred Pro II', 'Lenovo', 'Keyboards', 'PREFERRED-PRO-II', '39.99', 52],
            ['Dell KB216', 'Dell', 'Keyboards', 'KB216', '24.99', 75],
            ['HP 230 Wireless Keyboard', 'HP', 'Keyboards', 'HP230-KB', '29.99', 61],
            ['Asus ROG Strix Scope', 'Asus', 'Keyboards', 'ROG-SCOPE', '129.99', 28],
            ['Acer Predator Aethon 301', 'Acer', 'Keyboards', 'AETHON301', '69.99', 33],
            ['Xiaomi Mi Wireless Keyboard', 'Xiaomi', 'Keyboards', 'MI-KB-WL', '34.99', 55],
            ['Sony PlayStation Keyboard', 'Sony', 'Keyboards', 'PS-KB', '49.99', 18],

            ['Apple Magic Mouse', 'Apple', 'Mice', 'MAGIC-MOUSE', '89.99', 40],
            ['Lenovo ThinkPad Bluetooth Mouse', 'Lenovo', 'Mice', 'TP-BT-MOUSE', '39.99', 46],
            ['Dell MS3320W', 'Dell', 'Mice', 'MS3320W', '24.99', 66],
            ['HP Z3700 Wireless Mouse', 'HP', 'Mice', 'Z3700', '19.99', 88],
            ['Asus ROG Gladius III', 'Asus', 'Mice', 'ROG-GLADIUS3', '89.99', 27],
            ['Acer Predator Cestus 350', 'Acer', 'Mice', 'CESTUS350', '59.99', 29],
            ['Xiaomi Mi Dual Mode Mouse', 'Xiaomi', 'Mice', 'MI-DUAL-MOUSE', '24.99', 73],
            ['Samsung Bluetooth Mouse Slim', 'Samsung', 'Mice', 'BT-MOUSE-SLIM', '29.99', 45],
            ['Sony Vaio Wireless Mouse', 'Sony', 'Mice', 'VAIO-MOUSE', '34.99', 16],
            ['LG UltraGear Gaming Mouse', 'LG', 'Mice', 'ULTRAGEAR-MOUSE', '49.99', 25],

            ['HP LaserJet Pro M404dn', 'HP', 'Printers', 'M404DN', '249.99', 20],
            ['HP OfficeJet Pro 9010e', 'HP', 'Printers', '9010E', '199.99', 23],
            ['Samsung Xpress M2026W', 'Samsung', 'Printers', 'M2026W', '129.99', 17],
            ['Dell B2360dn Laser Printer', 'Dell', 'Printers', 'B2360DN', '219.99', 12],
            ['Lenovo LJ2405D', 'Lenovo', 'Printers', 'LJ2405D', '159.99', 19],
            ['Xiaomi Mi Portable Photo Printer', 'Xiaomi', 'Printers', 'MI-PHOTO-PRINTER', '79.99', 41],
            ['Acer Portable Mini Printer', 'Acer', 'Printers', 'ACER-MINI-PRINT', '69.99', 33],
            ['Asus Compact Printer AP200', 'Asus', 'Printers', 'AP200', '99.99', 21],
            ['LG Pocket Photo Printer', 'LG', 'Printers', 'PD239', '89.99', 37],
            ['Sony Digital Photo Printer', 'Sony', 'Printers', 'DPP-FP95', '149.99', 10],

            ['Sony Alpha A7 IV', 'Sony', 'Cameras', 'ILCE-7M4', '2499.99', 8],
            ['Sony Alpha A6400', 'Sony', 'Cameras', 'ILCE-6400', '999.99', 14],
            ['Sony ZV-E10', 'Sony', 'Cameras', 'ZV-E10', '749.99', 18],
            ['Samsung Galaxy Camera 2', 'Samsung', 'Cameras', 'EK-GC200', '399.99', 9],
            ['Apple iPhone Camera Kit', 'Apple', 'Cameras', 'CAMERA-KIT', '39.99', 70],
            ['Xiaomi Outdoor Camera AW300', 'Xiaomi', 'Cameras', 'AW300', '59.99', 64],
            ['Xiaomi Smart Camera C300', 'Xiaomi', 'Cameras', 'C300', '49.99', 80],
            ['Asus ROG Eye S', 'Asus', 'Cameras', 'ROG-EYE-S', '99.99', 24],
            ['Acer Full HD Webcam', 'Acer', 'Cameras', 'ACER-FHD-WEBCAM', '49.99', 36],
            ['Lenovo 300 FHD Webcam', 'Lenovo', 'Cameras', 'LENOVO-300-FHD', '39.99', 43],

            ['Apple USB-C Power Adapter', 'Apple', 'Accessories', 'USB-C-20W', '24.99', 100],
            ['Apple MagSafe Charger', 'Apple', 'Accessories', 'MAGSAFE-CHARGER', '49.99', 75],
            ['Samsung 25W Power Adapter', 'Samsung', 'Accessories', 'EP-TA800', '19.99', 95],
            ['Samsung SmartTag2', 'Samsung', 'Accessories', 'SMARTTAG2', '29.99', 60],
            ['Sony DualSense Controller', 'Sony', 'Accessories', 'DUALSENSE', '69.99', 50],
            ['Lenovo USB-C Dock Gen 2', 'Lenovo', 'Accessories', 'USB-C-DOCK-G2', '179.99', 22],
            ['Dell WD19S Docking Station', 'Dell', 'Accessories', 'WD19S', '219.99', 18],
            ['HP USB-C Dock G5', 'HP', 'Accessories', 'USB-C-DOCK-G5', '199.99', 20],
            ['Asus ROG Ally Travel Case', 'Asus', 'Accessories', 'ALLY-CASE', '39.99', 44],
            ['Xiaomi Mi 33W Charger', 'Xiaomi', 'Accessories', 'MI-33W-CHARGER', '24.99', 85],
        ];

        foreach ($products as [$name, $brandName, $categoryName, $model, $price, $stockQuantity]) {
            $existingProduct = $productRepository->findOneBy([
                'model' => $model,
            ]);

            if ($existingProduct !== null) {
                continue;
            }

            $brand = $brandRepository->findOneBy([
                'name' => $brandName,
            ]);

            $category = $categoryRepository->findOneBy([
                'name' => $categoryName,
            ]);

            if ($brand === null || $category === null) {
                throw new \RuntimeException(sprintf(
                    'Missing brand or category for product "%s".',
                    $name
                ));
            }

            $product = new Product();
            $product->setName($name);
            $product->setPrice($price);
            $product->setStockQuantity($stockQuantity);
            $product->setModel($model);
            $product->setBrandName($brandName);
            $product->setBrand($brand);
            $product->setManufacturer($brand);
            $product->setCategory($category);

            $this->em->persist($product);
        }

        $this->em->flush();
    }

    public function down(Schema $schema): void
    {
        $productRepository = $this->em->getRepository(Product::class);

        $models = [
            'A3090', 'A3102', 'A2882', 'SM-S921B', 'SM-S928B', 'SM-A556B', 'XIAOMI-14', 'RN13-PRO', 'XQ-DQ54', 'XQ-DC54',
            'MBA13-M2', 'MBA15-M2', 'MBP14-M3', 'X1-CARBON-G11', 'IPS5-14', 'XPS13-9340', 'INS15-3530', 'SPX360-14', 'PAV15-EG', 'UX3405',
            'IPAD10', 'IPADAIR5', 'IPADPRO11', 'TAB-S9', 'TAB-A9PLUS', 'TAB-P12', 'TAB-M10', 'PAD6', 'PAD-SE', 'ICONIA-TAB',
            '27GP850', '27UN880', 'ODYSSEY-G5', 'ODYSSEY-G7', 'U2723QE', 'S2721QS', 'M27FW', 'PA278QV', 'VG271U', 'P27H-30',
            'WH1000XM5', 'WH1000XM4', 'WF1000XM5', 'AIRPODS-PRO2', 'AIRPODS3', 'BUDS2-PRO', 'BUDS-FE', 'BUDS5-PRO', 'CLOUD-II', 'ROG-DELTA-S',
            'MAGIC-KEYBOARD', 'MAGIC-KB-TID', 'TRIO500', 'PREFERRED-PRO-II', 'KB216', 'HP230-KB', 'ROG-SCOPE', 'AETHON301', 'MI-KB-WL', 'PS-KB',
            'MAGIC-MOUSE', 'TP-BT-MOUSE', 'MS3320W', 'Z3700', 'ROG-GLADIUS3', 'CESTUS350', 'MI-DUAL-MOUSE', 'BT-MOUSE-SLIM', 'VAIO-MOUSE', 'ULTRAGEAR-MOUSE',
            'M404DN', '9010E', 'M2026W', 'B2360DN', 'LJ2405D', 'MI-PHOTO-PRINTER', 'ACER-MINI-PRINT', 'AP200', 'PD239', 'DPP-FP95',
            'ILCE-7M4', 'ILCE-6400', 'ZV-E10', 'EK-GC200', 'CAMERA-KIT', 'AW300', 'C300', 'ROG-EYE-S', 'ACER-FHD-WEBCAM', 'LENOVO-300-FHD',
            'USB-C-20W', 'MAGSAFE-CHARGER', 'EP-TA800', 'SMARTTAG2', 'DUALSENSE', 'USB-C-DOCK-G2', 'WD19S', 'USB-C-DOCK-G5', 'ALLY-CASE', 'MI-33W-CHARGER',
        ];

        foreach ($models as $model) {
            $product = $productRepository->findOneBy([
                'model' => $model,
            ]);

            if ($product !== null) {
                $this->em->remove($product);
            }
        }

        $this->em->flush();
    }
}
