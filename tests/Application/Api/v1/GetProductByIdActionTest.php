<?php

declare(strict_types=1);

namespace App\Tests\Application\Api\v1;

use App\Repository\ProductRepository;
use App\Repository\ProductRepositoryInterface;
use App\Tests\Application\ApiTestCase;
use App\Tests\Helpers\CreateProductTrait;
use App\Tests\Helpers\ExtractResponseTrait;

final class GetProductByIdActionTest extends ApiTestCase
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
        $name = 'keyboard';
        $manufacturer = 'apple';
        $price = '89.90';
        $categories = ['categories'];
        $eanCodes = ['47397593'];
        $this->createProduct($this->repository, $name, $manufacturer, $price, $categories, $eanCodes);
        $id = $this->repository->getAll()[0]['id'];

        $this->client->request('GET', self::ENDPOINT.'/'.$id);
        self::assertResponseIsSuccessful();

        $this->assertSame(
            [
                'code' => 200,
                'message' => 'A product with the id provided',
                'data' => [
                    'id' => $id,
                    'name' => $name,
                    'manufacturer' => $manufacturer,
                    'price' => $price,
                    'categories' => $categories,
                    'ean_codes' => $eanCodes,
                ],
            ],
            $this->extractResponse()
        );
    }

    public function test404NotFound(): void
    {
        $this->client->request('GET', self::ENDPOINT.'/2c1c7d67-03a5-4e09-a68e-a8b23e8a8d61');
        self::assertResponseStatusCodeSame(404);
    }

    public function test400BadRequest(): void
    {
        $this->client->request(
            'GET',
            self::ENDPOINT.'/not_valid'
        );

        self::assertResponseStatusCodeSame(400);

        $this->assertSame(
            [
                'code' => 400,
                'message' => 'Request needs modification',
                'errors' => [
                    [
                        'field' => 'id',
                        'message' => 'This is not a valid UUID.',
                    ],
                ],
            ],
            $this->extractResponse()
        );
    }
}
