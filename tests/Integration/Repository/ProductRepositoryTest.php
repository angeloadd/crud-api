<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\ProductRepositoryInterface;
use App\Tests\Helpers\CreateProductTrait;
use App\Tests\Integration\IntegrationTestCase;

final class ProductRepositoryTest extends IntegrationTestCase
{
    use CreateProductTrait;

    private ProductRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $repository = $this->container->get(ProductRepositoryInterface::class);
        $this->assertInstanceOf(ProductRepository::class, $repository);
        $this->repository = $repository;
    }

    public function testCreateProduct(): void
    {
        $name = 'Keyboard';
        $eanCodes = [
            '439729347924',
            '2431082134083',
        ];
        $manufacturer = 'Apple';
        $categories = [
            'Electronics',
            'Apple',
        ];
        $price = '89.90';
        $this->repository->createProduct(
            $name,
            $manufacturer,
            $price,
            $categories,
            $eanCodes,
        );

        $this->assertEntityOnDatabase(
            Product::class,
            'name',
            [
                'name' => $name,
                'manufacturer' => $manufacturer,
                'ean_codes' => $eanCodes,
                'categories' => $categories,
                'price' => $price,
            ]
        );
    }

    public function testGetAll(): void
    {
        $name = 'Keyboard';
        $eanCodes = [
            '439729347924',
            '2431082134083',
        ];
        $manufacturer = 'Apple';
        $categories = [
            'Electronics',
            'Apple',
        ];
        $price = '89.90';
        $this->createProduct(
            $this->repository,
            $name,
            $manufacturer,
            $price,
            $categories,
            $eanCodes,
        );
        $name2 = 'Mouse';
        $manufacturer2 = 'Acer';
        $price2 = '30.90';
        $categories2 = ['Electronics', 'Accessories'];
        $eanCodes2 = ['5433497', '04832023'];
        $this->createProduct(
            $this->repository,
            $name2,
            $manufacturer2,
            $price2,
            $categories2,
            $eanCodes2
        );

        $all = $this->repository->getAll();
        unset($all[0]['id'], $all[1]['id']);
        $this->assertSame(
            [
                [
                    'name' => $name,
                    'manufacturer' => $manufacturer,
                    'price' => $price,
                    'categories' => $categories,
                    'ean_codes' => $eanCodes,
                ],
                [
                    'name' => $name2,
                    'manufacturer' => $manufacturer2,
                    'price' => $price2,
                    'categories' => $categories2,
                    'ean_codes' => $eanCodes2,
                ],
            ],
            $all
        );
    }

    public function testDeleteById(): void
    {
        $this->createProduct(
            $this->repository,
            'Keyboard',
            'Apple',
            '89.90',
            ['Apple'],
            ['3423408'],
        );

        $id = $this->repository->getAll()[0]['id'];

        $this->repository->deleteById($id);

        $this->assertEmpty($this->repository->getAll());
    }

    public function testGetById(): void
    {
        $name = 'Keyboard';
        $manufacturer = 'Apple';
        $price = '89.90';
        $categories = ['Apple'];
        $eanCodes = ['3423408'];
        $this->createProduct(
            $this->repository,
            $name,
            $manufacturer,
            $price,
            $categories,
            $eanCodes,
        );

        $id = $this->repository->getAll()[0]['id'];

        $this->repository->getById($id);

        $this->assertSame(
            [
                'id' => $id,
                'name' => $name,
                'manufacturer' => $manufacturer,
                'price' => $price,
                'categories' => $categories,
                'ean_codes' => $eanCodes,
            ],
            $this->repository->getById($id)
        );
    }

    public function testUpdateById(): void
    {
        $this->createProduct($this->repository, 'name', 'manu', '89.90', ['cat'], ['42393']);
        $id = $this->repository->getAll()[0]['id'];

        $name = 'newName';
        $manufacturer = 'newMany';
        $price = '79.90';
        $categories = ['cat', 'other'];
        $eanCodes = ['39427923'];

        $ciao = $this->repository->updateById(
            $id,
            $name,
            $manufacturer,
            $price,
            $categories,
            $eanCodes
        );

        $this->assertTrue($ciao);

        $this->entityManager->clear();
        $updated = $this->repository->getAll()[0];

        $this->assertSame(
            [
                'id' => $id,
                'name' => $name,
                'manufacturer' => $manufacturer,
                'price' => $price,
                'categories' => $categories,
                'ean_codes' => $eanCodes,
            ],
            $updated
        );
    }
}
