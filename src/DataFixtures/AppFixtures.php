<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductVariant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer les catégories
        $categories = $this->createCategories($manager);

        // Créer les marques
        $brands = $this->createBrands($manager);

        // Créer les produits
        $this->createProducts($manager, $categories, $brands);

        $manager->flush();
    }

    private function createCategories(ObjectManager $manager): array
    {
        $categoriesData = [
            ['name' => 'Running', 'slug' => 'running', 'description' => 'Chaussures de course haute performance'],
            ['name' => 'Casual', 'slug' => 'casual', 'description' => 'Chaussures décontractées pour tous les jours'],
            ['name' => 'Sport', 'slug' => 'sport', 'description' => 'Chaussures de sport et fitness'],
            ['name' => 'Luxe', 'slug' => 'luxe', 'description' => 'Collection premium et luxueuse'],
            ['name' => 'Sneakers', 'slug' => 'sneakers', 'description' => 'Sneakers tendance et streetwear'],
            ['name' => 'Basketball', 'slug' => 'basketball', 'description' => 'Chaussures de basketball'],
        ];

        $categories = [];
        foreach ($categoriesData as $data) {
            $category = new Category();
            $category->setName($data['name']);
            $category->setSlug($data['slug']);
            $category->setDescription($data['description']);
            $manager->persist($category);
            $categories[$data['slug']] = $category;
        }

        return $categories;
    }

    private function createBrands(ObjectManager $manager): array
    {
        $brandsData = [
            ['name' => 'Nike', 'slug' => 'nike', 'description' => 'Just Do It - Leader mondial du sportswear'],
            ['name' => 'Adidas', 'slug' => 'adidas', 'description' => 'Impossible is Nothing - Performance et style'],
            ['name' => 'Puma', 'slug' => 'puma', 'description' => 'Forever Faster - Innovation sportive'],
            ['name' => 'New Balance', 'slug' => 'new-balance', 'description' => 'Made in USA - Qualité premium'],
            ['name' => 'Jordan', 'slug' => 'jordan', 'description' => 'Jumpman - Héritage basketball'],
            ['name' => 'Converse', 'slug' => 'converse', 'description' => 'All Star - Icône streetwear'],
            ['name' => 'Reebok', 'slug' => 'reebok', 'description' => 'Be More Human - Fitness et lifestyle'],
            ['name' => 'Asics', 'slug' => 'asics', 'description' => 'Sound Mind, Sound Body - Running expert'],
        ];

        $brands = [];
        foreach ($brandsData as $data) {
            $brand = new Brand();
            $brand->setName($data['name']);
            $brand->setSlug($data['slug']);
            $brand->setDescription($data['description']);
            $manager->persist($brand);
            $brands[$data['slug']] = $brand;
        }

        return $brands;
    }

    private function createProducts(ObjectManager $manager, array $categories, array $brands): void
    {
        $productsData = [
            [
                'name' => 'Nike Air Max 270',
                'slug' => 'nike-air-max-270',
                'description' => 'La Nike Air Max 270 offre un confort exceptionnel grâce à sa grande unité Air sous le talon. Design moderne et respirant pour un usage quotidien.',
                'price' => '149.99',
                'discountPrice' => '129.99',
                'category' => 'sneakers',
                'brand' => 'nike',
                'isFeatured' => true,
                'isNewArrival' => true,
                'images' => [
                    'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/zwxes8uud05rkuei1mpt/AIR+MAX+270.png',
                    'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/i1-6f774481-5bb4-49e2-a4ba-48a8997c3b56/AIR+MAX+270.png'
                ],
                'variants' => [
                    ['size' => '39', 'color' => 'Noir', 'colorCode' => '#000000', 'sku' => 'NAM270-BLK-39', 'stock' => 15],
                    ['size' => '40', 'color' => 'Noir', 'colorCode' => '#000000', 'sku' => 'NAM270-BLK-40', 'stock' => 20],
                    ['size' => '41', 'color' => 'Noir', 'colorCode' => '#000000', 'sku' => 'NAM270-BLK-41', 'stock' => 18],
                    ['size' => '42', 'color' => 'Noir', 'colorCode' => '#000000', 'sku' => 'NAM270-BLK-42', 'stock' => 22],
                    ['size' => '43', 'color' => 'Noir', 'colorCode' => '#000000', 'sku' => 'NAM270-BLK-43', 'stock' => 12],
                    ['size' => '39', 'color' => 'Blanc', 'colorCode' => '#FFFFFF', 'sku' => 'NAM270-WHT-39', 'stock' => 10],
                    ['size' => '40', 'color' => 'Blanc', 'colorCode' => '#FFFFFF', 'sku' => 'NAM270-WHT-40', 'stock' => 14],
                    ['size' => '41', 'color' => 'Blanc', 'colorCode' => '#FFFFFF', 'sku' => 'NAM270-WHT-41', 'stock' => 16],
                    ['size' => '42', 'color' => 'Blanc', 'colorCode' => '#FFFFFF', 'sku' => 'NAM270-WHT-42', 'stock' => 19],
                ],
            ],
            [
                'name' => 'Adidas Ultraboost 23',
                'slug' => 'adidas-ultraboost-23',
                'description' => 'Technologie Boost révolutionnaire pour un retour d\'énergie optimal. Semelle Continental pour une adhérence maximale. Parfait pour le running.',
                'price' => '189.99',
                'category' => 'running',
                'brand' => 'adidas',
                'isFeatured' => true,
                'isNewArrival' => true,
                'images' => [
                    'https://assets.adidas.com/images/h_840,f_auto,q_auto,fl_lossy,c_fill,g_auto/fbaf991a78bc4896a3e9ad7800abcec6_9366/Ultraboost_Light_Shoes_Black_GY9350_01_standard.jpg',
                    'https://assets.adidas.com/images/h_840,f_auto,q_auto,fl_lossy,c_fill,g_auto/e0d99da8fa254d95bb8cad7800abd2fc_9366/Ultraboost_Light_Shoes_Black_GY9350_02_standard_hover.jpg'
                ],
                'variants' => [
                    ['size' => '40', 'color' => 'Core Black', 'colorCode' => '#1a1a1a', 'sku' => 'AUB23-BLK-40', 'stock' => 12],
                    ['size' => '41', 'color' => 'Core Black', 'colorCode' => '#1a1a1a', 'sku' => 'AUB23-BLK-41', 'stock' => 15],
                    ['size' => '42', 'color' => 'Core Black', 'colorCode' => '#1a1a1a', 'sku' => 'AUB23-BLK-42', 'stock' => 18],
                    ['size' => '43', 'color' => 'Core Black', 'colorCode' => '#1a1a1a', 'sku' => 'AUB23-BLK-43', 'stock' => 14],
                    ['size' => '40', 'color' => 'Triple White', 'colorCode' => '#F5F5F5', 'sku' => 'AUB23-WHT-40', 'stock' => 8],
                    ['size' => '41', 'color' => 'Triple White', 'colorCode' => '#F5F5F5', 'sku' => 'AUB23-WHT-41', 'stock' => 11],
                    ['size' => '42', 'color' => 'Triple White', 'colorCode' => '#F5F5F5', 'sku' => 'AUB23-WHT-42', 'stock' => 13],
                ],
            ],
            [
                'name' => 'Jordan 1 Retro High',
                'slug' => 'jordan-1-retro-high',
                'description' => 'L\'iconique Air Jordan 1 dans sa version montante. Design intemporel qui a révolutionné le basketball. Collection heritage.',
                'price' => '179.99',
                'discountPrice' => '159.99',
                'category' => 'basketball',
                'brand' => 'jordan',
                'isFeatured' => true,
                'isNewArrival' => false,
                'images' => [
                    'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/b7d9211c-26e7-431a-ac24-b0540fb3c00f/AIR+JORDAN+1+RETRO+HIGH+OG.png',
                    'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/0f42c0de-aa09-4e7b-b0e8-3c4e2a37d31d/AIR+JORDAN+1+RETRO+HIGH+OG.png'
                ],
                'variants' => [
                    ['size' => '39', 'color' => 'Chicago', 'colorCode' => '#DC143C', 'sku' => 'J1RH-CHI-39', 'stock' => 5],
                    ['size' => '40', 'color' => 'Chicago', 'colorCode' => '#DC143C', 'sku' => 'J1RH-CHI-40', 'stock' => 8],
                    ['size' => '41', 'color' => 'Chicago', 'colorCode' => '#DC143C', 'sku' => 'J1RH-CHI-41', 'stock' => 6],
                    ['size' => '42', 'color' => 'Chicago', 'colorCode' => '#DC143C', 'sku' => 'J1RH-CHI-42', 'stock' => 10],
                    ['size' => '43', 'color' => 'Chicago', 'colorCode' => '#DC143C', 'sku' => 'J1RH-CHI-43', 'stock' => 7],
                    ['size' => '40', 'color' => 'Bred', 'colorCode' => '#000000', 'sku' => 'J1RH-BRD-40', 'stock' => 4],
                    ['size' => '41', 'color' => 'Bred', 'colorCode' => '#000000', 'sku' => 'J1RH-BRD-41', 'stock' => 3],
                    ['size' => '42', 'color' => 'Bred', 'colorCode' => '#000000', 'sku' => 'J1RH-BRD-42', 'stock' => 5],
                ],
            ],
            [
                'name' => 'New Balance 574 Classic',
                'slug' => 'new-balance-574-classic',
                'description' => 'Le modèle emblématique de New Balance. Confort légendaire avec semelle ENCAP. Style rétro qui traverse les décennies.',
                'price' => '99.99',
                'category' => 'casual',
                'brand' => 'new-balance',
                'isFeatured' => false,
                'isNewArrival' => false,
                'images' => [
                    'https://nb.scene7.com/is/image/NB/ml574evg_nb_02_i?$pdpflexf2$&qlt=80&fmt=webp&wid=440&hei=440',
                    'https://nb.scene7.com/is/image/NB/ml574evg_nb_05_i?$pdpflexf2$&qlt=80&fmt=webp&wid=440&hei=440'
                ],
                'variants' => [
                    ['size' => '39', 'color' => 'Grey', 'colorCode' => '#808080', 'sku' => 'NB574-GRY-39', 'stock' => 20],
                    ['size' => '40', 'color' => 'Grey', 'colorCode' => '#808080', 'sku' => 'NB574-GRY-40', 'stock' => 25],
                    ['size' => '41', 'color' => 'Grey', 'colorCode' => '#808080', 'sku' => 'NB574-GRY-41', 'stock' => 22],
                    ['size' => '42', 'color' => 'Grey', 'colorCode' => '#808080', 'sku' => 'NB574-GRY-42', 'stock' => 28],
                    ['size' => '43', 'color' => 'Grey', 'colorCode' => '#808080', 'sku' => 'NB574-GRY-43', 'stock' => 15],
                    ['size' => '39', 'color' => 'Navy', 'colorCode' => '#000080', 'sku' => 'NB574-NVY-39', 'stock' => 18],
                    ['size' => '40', 'color' => 'Navy', 'colorCode' => '#000080', 'sku' => 'NB574-NVY-40', 'stock' => 21],
                    ['size' => '41', 'color' => 'Navy', 'colorCode' => '#000080', 'sku' => 'NB574-NVY-41', 'stock' => 19],
                ],
            ],
            [
                'name' => 'Puma RS-X Bold',
                'slug' => 'puma-rs-x-bold',
                'description' => 'Design audacieux inspiré des années 80. Amorti RS optimal pour le confort. Couleurs vives et lignes dynamiques.',
                'price' => '119.99',
                'discountPrice' => '94.99',
                'category' => 'sneakers',
                'brand' => 'puma',
                'isFeatured' => true,
                'isNewArrival' => false,
                'images' => [
                    'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_600,h_600/global/393169/01/sv01/fnd/PNA/fmt/png/RS-X-Bold-Sneakers',
                    'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_600,h_600/global/393169/01/bv/fnd/PNA/fmt/png/RS-X-Bold-Sneakers'
                ],
                'variants' => [
                    ['size' => '39', 'color' => 'Multicolor', 'colorCode' => '#FF6B6B', 'sku' => 'PRSX-MLT-39', 'stock' => 12],
                    ['size' => '40', 'color' => 'Multicolor', 'colorCode' => '#FF6B6B', 'sku' => 'PRSX-MLT-40', 'stock' => 16],
                    ['size' => '41', 'color' => 'Multicolor', 'colorCode' => '#FF6B6B', 'sku' => 'PRSX-MLT-41', 'stock' => 14],
                    ['size' => '42', 'color' => 'Multicolor', 'colorCode' => '#FF6B6B', 'sku' => 'PRSX-MLT-42', 'stock' => 18],
                    ['size' => '43', 'color' => 'Multicolor', 'colorCode' => '#FF6B6B', 'sku' => 'PRSX-MLT-43', 'stock' => 10],
                ],
            ],
            [
                'name' => 'Converse Chuck Taylor All Star',
                'slug' => 'converse-chuck-taylor-all-star',
                'description' => 'La sneaker légendaire qui a traversé les générations. Toile résistante et semelle en caoutchouc iconique. Un classique intemporel.',
                'price' => '69.99',
                'category' => 'casual',
                'brand' => 'converse',
                'isFeatured' => false,
                'isNewArrival' => false,
                'images' => [
                    'https://www.converse.com/dw/image/v2/BCZC_PRD/on/demandware.static/-/Sites-cnv-master-catalog/default/dw2b4ab51d/images/a_107/M9160_A_107X1.jpg?sw=964',
                    'https://www.converse.com/dw/image/v2/BCZC_PRD/on/demandware.static/-/Sites-cnv-master-catalog/default/dw7c8f0b31/images/a_107/M9160_A_107X2.jpg?sw=964'
                ],
                'variants' => [
                    ['size' => '38', 'color' => 'Black', 'colorCode' => '#000000', 'sku' => 'CVS-BLK-38', 'stock' => 30],
                    ['size' => '39', 'color' => 'Black', 'colorCode' => '#000000', 'sku' => 'CVS-BLK-39', 'stock' => 35],
                    ['size' => '40', 'color' => 'Black', 'colorCode' => '#000000', 'sku' => 'CVS-BLK-40', 'stock' => 32],
                    ['size' => '41', 'color' => 'Black', 'colorCode' => '#000000', 'sku' => 'CVS-BLK-41', 'stock' => 28],
                    ['size' => '42', 'color' => 'Black', 'colorCode' => '#000000', 'sku' => 'CVS-BLK-42', 'stock' => 25],
                    ['size' => '43', 'color' => 'Black', 'colorCode' => '#000000', 'sku' => 'CVS-BLK-43', 'stock' => 20],
                    ['size' => '38', 'color' => 'White', 'colorCode' => '#FFFFFF', 'sku' => 'CVS-WHT-38', 'stock' => 28],
                    ['size' => '39', 'color' => 'White', 'colorCode' => '#FFFFFF', 'sku' => 'CVS-WHT-39', 'stock' => 30],
                    ['size' => '40', 'color' => 'White', 'colorCode' => '#FFFFFF', 'sku' => 'CVS-WHT-40', 'stock' => 26],
                    ['size' => '41', 'color' => 'White', 'colorCode' => '#FFFFFF', 'sku' => 'CVS-WHT-41', 'stock' => 24],
                ],
            ],
            [
                'name' => 'Asics Gel-Kayano 30',
                'slug' => 'asics-gel-kayano-30',
                'description' => 'Chaussure de running avec technologie GEL pour un amorti optimal. Support de la voûte plantaire pour les longues distances.',
                'price' => '169.99',
                'category' => 'running',
                'brand' => 'asics',
                'isFeatured' => false,
                'isNewArrival' => true,
                'images' => [
                    'https://images.asics.com/is/image/asics/1011B440_400_SR_RT_GLB?$zoom$',
                    'https://images.asics.com/is/image/asics/1011B440_400_SB_FL_GLB?$zoom$'
                ],
                'variants' => [
                    ['size' => '40', 'color' => 'Blue', 'colorCode' => '#0ea5e9', 'sku' => 'ASC-BLU-40', 'stock' => 10],
                    ['size' => '41', 'color' => 'Blue', 'colorCode' => '#0ea5e9', 'sku' => 'ASC-BLU-41', 'stock' => 12],
                    ['size' => '42', 'color' => 'Blue', 'colorCode' => '#0ea5e9', 'sku' => 'ASC-BLU-42', 'stock' => 14],
                    ['size' => '43', 'color' => 'Blue', 'colorCode' => '#0ea5e9', 'sku' => 'ASC-BLU-43', 'stock' => 11],
                    ['size' => '40', 'color' => 'Black', 'colorCode' => '#000000', 'sku' => 'ASC-BLK-40', 'stock' => 9],
                    ['size' => '41', 'color' => 'Black', 'colorCode' => '#000000', 'sku' => 'ASC-BLK-41', 'stock' => 13],
                    ['size' => '42', 'color' => 'Black', 'colorCode' => '#000000', 'sku' => 'ASC-BLK-42', 'stock' => 15],
                ],
            ],
            [
                'name' => 'Nike ZoomX Vaporfly',
                'slug' => 'nike-zoomx-vaporfly',
                'description' => 'Chaussure de running compétition. Mousse ZoomX ultra-légère et plaque carbone. Pour battre vos records personnels.',
                'price' => '259.99',
                'category' => 'running',
                'brand' => 'nike',
                'isFeatured' => true,
                'isNewArrival' => true,
                'images' => [
                    'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/99286ac2-81c5-4f03-bf00-0d04ca8c79b2/ZOOMX+VAPORFLY+NEXT%25+3.png',
                    'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/a2c4814c-1f6d-4e70-9d67-5e67dc5b5a42/ZOOMX+VAPORFLY+NEXT%25+3.png'
                ],
                'variants' => [
                    ['size' => '40', 'color' => 'Volt', 'colorCode' => '#DFFF00', 'sku' => 'NZV-VLT-40', 'stock' => 6],
                    ['size' => '41', 'color' => 'Volt', 'colorCode' => '#DFFF00', 'sku' => 'NZV-VLT-41', 'stock' => 8],
                    ['size' => '42', 'color' => 'Volt', 'colorCode' => '#DFFF00', 'sku' => 'NZV-VLT-42', 'stock' => 7],
                    ['size' => '43', 'color' => 'Volt', 'colorCode' => '#DFFF00', 'sku' => 'NZV-VLT-43', 'stock' => 5],
                    ['size' => '40', 'color' => 'Pink Blast', 'colorCode' => '#FF69B4', 'sku' => 'NZV-PNK-40', 'stock' => 4],
                    ['size' => '41', 'color' => 'Pink Blast', 'colorCode' => '#FF69B4', 'sku' => 'NZV-PNK-41', 'stock' => 6],
                    ['size' => '42', 'color' => 'Pink Blast', 'colorCode' => '#FF69B4', 'sku' => 'NZV-PNK-42', 'stock' => 5],
                ],
            ],
            [
                'name' => 'Adidas Stan Smith',
                'slug' => 'adidas-stan-smith',
                'description' => 'Tennis shoe devenue icône lifestyle. Design minimaliste et élégant. Cuir de qualité premium pour un look sophistiqué.',
                'price' => '89.99',
                'category' => 'casual',
                'brand' => 'adidas',
                'isFeatured' => false,
                'isNewArrival' => false,
                'images' => [
                    'https://assets.adidas.com/images/h_840,f_auto,q_auto,fl_lossy,c_fill,g_auto/3bbecbdf584e40398446a8bf0117cf62_9366/Stan_Smith_Shoes_White_M20324_01_standard.jpg',
                    'https://assets.adidas.com/images/h_840,f_auto,q_auto,fl_lossy,c_fill,g_auto/d0720712d81e42b1b30fa8bf0117d7d6_9366/Stan_Smith_Shoes_White_M20324_02_standard_hover.jpg'
                ],
                'variants' => [
                    ['size' => '38', 'color' => 'White/Green', 'colorCode' => '#FFFFFF', 'sku' => 'ASS-WGR-38', 'stock' => 22],
                    ['size' => '39', 'color' => 'White/Green', 'colorCode' => '#FFFFFF', 'sku' => 'ASS-WGR-39', 'stock' => 25],
                    ['size' => '40', 'color' => 'White/Green', 'colorCode' => '#FFFFFF', 'sku' => 'ASS-WGR-40', 'stock' => 28],
                    ['size' => '41', 'color' => 'White/Green', 'colorCode' => '#FFFFFF', 'sku' => 'ASS-WGR-41', 'stock' => 26],
                    ['size' => '42', 'color' => 'White/Green', 'colorCode' => '#FFFFFF', 'sku' => 'ASS-WGR-42', 'stock' => 24],
                    ['size' => '43', 'color' => 'White/Green', 'colorCode' => '#FFFFFF', 'sku' => 'ASS-WGR-43', 'stock' => 20],
                ],
            ],
            [
                'name' => 'Reebok Club C 85',
                'slug' => 'reebok-club-c-85',
                'description' => 'Sneaker rétro inspirée du tennis. Confort optimal et style vintage. Parfait pour un look décontracté chic.',
                'price' => '79.99',
                'discountPrice' => '64.99',
                'category' => 'casual',
                'brand' => 'reebok',
                'isFeatured' => false,
                'isNewArrival' => false,
                'images' => [
                    'https://images.reebok.com/reebok-club-c-85-shoes/100074060.jpg',
                    'https://images.reebok.com/reebok-club-c-85-shoes/100074060_2.jpg'
                ],
                'variants' => [
                    ['size' => '39', 'color' => 'White', 'colorCode' => '#FFFFFF', 'sku' => 'RCC-WHT-39', 'stock' => 16],
                    ['size' => '40', 'color' => 'White', 'colorCode' => '#FFFFFF', 'sku' => 'RCC-WHT-40', 'stock' => 18],
                    ['size' => '41', 'color' => 'White', 'colorCode' => '#FFFFFF', 'sku' => 'RCC-WHT-41', 'stock' => 20],
                    ['size' => '42', 'color' => 'White', 'colorCode' => '#FFFFFF', 'sku' => 'RCC-WHT-42', 'stock' => 17],
                    ['size' => '43', 'color' => 'White', 'colorCode' => '#FFFFFF', 'sku' => 'RCC-WHT-43', 'stock' => 14],
                ],
            ],
            [
                'name' => 'Nike Dunk Low',
                'slug' => 'nike-dunk-low',
                'description' => 'Sneaker basketball devenue phénomène streetwear. Coloris variés et design intemporel. Collection très recherchée.',
                'price' => '119.99',
                'category' => 'sneakers',
                'brand' => 'nike',
                'isFeatured' => true,
                'isNewArrival' => true,
                'images' => [
                    'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/350e7f3a-979a-402b-9396-a8a998dd76ab/NIKE+DUNK+LOW+RETRO.png',
                    'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/af53d53d-561f-450a-a483-70a7ceee380f/NIKE+DUNK+LOW+RETRO.png'
                ],
                'variants' => [
                    ['size' => '39', 'color' => 'Panda', 'colorCode' => '#000000', 'sku' => 'NDL-PND-39', 'stock' => 8],
                    ['size' => '40', 'color' => 'Panda', 'colorCode' => '#000000', 'sku' => 'NDL-PND-40', 'stock' => 10],
                    ['size' => '41', 'color' => 'Panda', 'colorCode' => '#000000', 'sku' => 'NDL-PND-41', 'stock' => 12],
                    ['size' => '42', 'color' => 'Panda', 'colorCode' => '#000000', 'sku' => 'NDL-PND-42', 'stock' => 9],
                    ['size' => '43', 'color' => 'Panda', 'colorCode' => '#000000', 'sku' => 'NDL-PND-43', 'stock' => 7],
                    ['size' => '40', 'color' => 'University Red', 'colorCode' => '#DC143C', 'sku' => 'NDL-RED-40', 'stock' => 5],
                    ['size' => '41', 'color' => 'University Red', 'colorCode' => '#DC143C', 'sku' => 'NDL-RED-41', 'stock' => 6],
                    ['size' => '42', 'color' => 'University Red', 'colorCode' => '#DC143C', 'sku' => 'NDL-RED-42', 'stock' => 4],
                ],
            ],
            [
                'name' => 'Puma Suede Classic',
                'slug' => 'puma-suede-classic',
                'description' => 'Icône du streetwear depuis 1968. Daim premium et design épuré. Histoire légendaire du hip-hop et du skate.',
                'price' => '74.99',
                'category' => 'sneakers',
                'brand' => 'puma',
                'isFeatured' => false,
                'isNewArrival' => false,
                'images' => [
                    'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_600,h_600/global/374915/01/sv01/fnd/PNA/fmt/png/Suede-Classic-XXI-Sneakers',
                    'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_600,h_600/global/374915/01/bv/fnd/PNA/fmt/png/Suede-Classic-XXI-Sneakers'
                ],
                'variants' => [
                    ['size' => '39', 'color' => 'Peacoat', 'colorCode' => '#1e3a8a', 'sku' => 'PSC-PCT-39', 'stock' => 14],
                    ['size' => '40', 'color' => 'Peacoat', 'colorCode' => '#1e3a8a', 'sku' => 'PSC-PCT-40', 'stock' => 16],
                    ['size' => '41', 'color' => 'Peacoat', 'colorCode' => '#1e3a8a', 'sku' => 'PSC-PCT-41', 'stock' => 18],
                    ['size' => '42', 'color' => 'Peacoat', 'colorCode' => '#1e3a8a', 'sku' => 'PSC-PCT-42', 'stock' => 15],
                    ['size' => '43', 'color' => 'Peacoat', 'colorCode' => '#1e3a8a', 'sku' => 'PSC-PCT-43', 'stock' => 12],
                    ['size' => '40', 'color' => 'Red', 'colorCode' => '#DC2626', 'sku' => 'PSC-RED-40', 'stock' => 10],
                    ['size' => '41', 'color' => 'Red', 'colorCode' => '#DC2626', 'sku' => 'PSC-RED-41', 'stock' => 11],
                    ['size' => '42', 'color' => 'Red', 'colorCode' => '#DC2626', 'sku' => 'PSC-RED-42', 'stock' => 9],
                ],
            ],
        ];

        foreach ($productsData as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setSlug($data['slug']);
            $product->setDescription($data['description']);
            $product->setPrice($data['price']);

            if (isset($data['discountPrice'])) {
                $product->setDiscountPrice($data['discountPrice']);
            }

            $product->setImages($data['images']);
            $product->setIsFeatured($data['isFeatured']);
            $product->setIsNewArrival($data['isNewArrival']);
            $product->setCategory($categories[$data['category']]);
            $product->setBrand($brands[$data['brand']]);

            $manager->persist($product);

            // Créer les variants
            foreach ($data['variants'] as $variantData) {
                $variant = new ProductVariant();
                $variant->setSize($variantData['size']);
                $variant->setColor($variantData['color']);
                $variant->setColorCode($variantData['colorCode']);
                $variant->setSku($variantData['sku']);
                $variant->setStock($variantData['stock']);
                $variant->setProduct($product);

                $manager->persist($variant);
            }
        }
    }
}
