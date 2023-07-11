<?php

declare(strict_types=1);

namespace App\Tests\Application\Api\v1;

use App\Repository\ProductRepository;
use App\Repository\ProductRepositoryInterface;
use App\Tests\Application\ApiTestCase;
use App\Tests\Helpers\CreateProductTrait;
use App\Tests\Helpers\ExtractResponseTrait;

final class GetProductsActionTest extends ApiTestCase
{
    use CreateProductTrait;
    use ExtractResponseTrait;

    private const ENDPOINT = '/api/v1/product';

    private ProductRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $repository = $this->container->get(ProductRepositoryInterface::class);
        $this->assertInstanceOf(ProductRepository::class, $repository);
        $this->repository = $repository;
    }

    public function test200Ok(): void
    {
        $name = 'Keyboard';
        $eanCodes = ['93249743'];
        $manufacturer = 'Apple';
        $price = '89.90';
        $this->createProduct(
            $this->repository,
            $name,
            $manufacturer,
            $price,
            [$manufacturer],
            $eanCodes
        );
        $name2 = 'Mouse';
        $eanCodes2 = ['4929743'];
        $this->createProduct(
            $this->repository,
            $name2,
            $manufacturer,
            $price,
            [$manufacturer],
            $eanCodes2
        );

        $this->client->request('GET', self::ENDPOINT);
        self::assertResponseIsSuccessful();

        $product = $this->repository->getAll();
        $this->assertSame(
            [
                'code' => 200,
                'message' => 'A list of all the products available',
                'data' => [
                    [
                        'id' => $product[0]['id'],
                        'name' => $name,
                        'manufacturer' => $manufacturer,
                        'price' => $price,
                        'categories' => [$manufacturer],
                        'ean_codes' => $eanCodes,
                    ],
                    [
                        'id' => $product[1]['id'],
                        'name' => $name2,
                        'manufacturer' => $manufacturer,
                        'price' => $price,
                        'categories' => [$manufacturer],
                        'ean_codes' => $eanCodes2,
                    ],
                ],
            ],
            $this->extractResponse()
        );
    }

    public function test404NotFound(): void
    {
        $this->client->request('GET', self::ENDPOINT);
        self::assertResponseStatusCodeSame(404);

        $this->assertSame(
            [
                'code' => 404,
                'message' => 'At the moment there are no products available. Please add a product.',
            ],
            $this->extractResponse()
        );
    }
}
