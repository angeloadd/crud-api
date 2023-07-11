<?php

declare(strict_types=1);

namespace App\Tests\Application\Api\v1;

use App\Repository\ProductRepository;
use App\Repository\ProductRepositoryInterface;
use App\Tests\Application\ApiTestCase;
use App\Tests\Helpers\CreateProductTrait;
use App\Tests\Helpers\ExtractResponseTrait;

final class UpdateProductByIdActionTest extends ApiTestCase
{
    use CreateProductTrait;
    use ExtractResponseTrait;

    public const ENDPOINT = '/api/v1/product';

    public function badRequestProvider(): \Generator
    {
        $id = '35290778-af4d-41d3-97a4-4856bac664cc';
        $name = 'Keyboard';
        $manufacturer = 'Apple';
        $price = '89.90';
        $categories = ['Elec', 'Apple'];
        $eanCodes = ['0324707430', '084302840'];

        yield 'missing id' => [
            [
                'name' => $name,
                'manufacturer' => $manufacturer,
                'categories' => $categories,
                'ean_codes' => $eanCodes,
                'price' => $price,
            ],
            [
                [
                    'field' => 'id',
                    'message' => 'This value should not be blank.',
                ],
            ],
        ];

        yield 'empty id' => [
            [
                'id' => '',
                'name' => $name,
                'manufacturer' => $manufacturer,
                'categories' => $categories,
                'ean_codes' => $eanCodes,
                'price' => $price,
            ],
            [
                [
                    'field' => 'id',
                    'message' => 'This value should not be blank.',
                ],
            ],
        ];

        yield 'not uuid' => [
            [
                'id' => 'not_valid',
                'name' => $name,
                'manufacturer' => $manufacturer,
                'categories' => $categories,
                'ean_codes' => $eanCodes,
                'price' => $price,
            ],
            [
                [
                    'field' => 'id',
                    'message' => 'This is not a valid UUID.',
                ],
            ],
        ];

        yield 'missing name' => [
            [
                'id' => $id,
                'manufacturer' => $manufacturer,
                'categories' => $categories,
                'ean_codes' => $eanCodes,
                'price' => $price,
            ],
            [
                [
                    'field' => 'name',
                    'message' => 'This value should not be blank.',
                ],
            ],
        ];

        yield 'empty name' => [
            [
                'id' => $id,
                'name' => '',
                'manufacturer' => $manufacturer,
                'categories' => $categories,
                'ean_codes' => $eanCodes,
                'price' => $price,
            ],
            [
                [
                    'field' => 'name',
                    'message' => 'This value should not be blank.',
                ],
            ],
        ];

        yield 'missing manufacturer' => [
            [
                'id' => $id,
                'name' => $name,
                'categories' => $categories,
                'ean_codes' => $eanCodes,
                'price' => $price,
            ],
            [
                [
                    'field' => 'manufacturer',
                    'message' => 'This value should not be blank.',
                ],
            ],
        ];

        yield 'empty manufacturer' => [
            [
                'id' => $id,
                'name' => $name,
                'manufacturer' => '',
                'categories' => $categories,
                'ean_codes' => $eanCodes,
                'price' => $price,
            ],
            [
                [
                    'field' => 'manufacturer',
                    'message' => 'This value should not be blank.',
                ],
            ],
        ];

        yield 'missing price' => [
            [
                'id' => $id,
                'name' => $name,
                'manufacturer' => $manufacturer,
                'categories' => $categories,
                'ean_codes' => $eanCodes,
            ],
            [
                [
                    'field' => 'price',
                    'message' => 'This value should not be blank.',
                ],
            ],
        ];

        yield 'empty price' => [
            [
                'id' => $id,
                'name' => $name,
                'manufacturer' => $manufacturer,
                'categories' => $categories,
                'ean_codes' => $eanCodes,
                'price' => '',
            ],
            [
                [
                    'field' => 'price',
                    'message' => 'This value should not be blank.',
                ],
            ],
        ];

        yield 'not following standard price' => [
            [
                'id' => $id,
                'name' => $name,
                'manufacturer' => $manufacturer,
                'categories' => $categories,
                'ean_codes' => $eanCodes,
                'price' => '10000.000',
            ],
            [
                [
                    'field' => 'price',
                    'message' => 'Price should follow the pattern of max 7 digits, dot, max 2 decimal, example: 1000000.00',
                ],
            ],
        ];

        yield 'missing ean codes' => [
            [
                'id' => $id,
                'name' => $name,
                'manufacturer' => $manufacturer,
                'categories' => $categories,
                'price' => $price,
            ],
            [
                [
                    'field' => 'ean_codes',
                    'message' => 'This value should not be blank.',
                ],
            ],
        ];

        yield 'not array ean codes' => [
            [
                'id' => $id,
                'name' => $name,
                'manufacturer' => $manufacturer,
                'categories' => $categories,
                'price' => $price,
                'ean_codes' => '',
            ],
            [
                [
                    'field' => 'ean_codes',
                    'message' => 'This value should not be blank.',
                ],
                [
                    'field' => 'ean_codes',
                    'message' => 'EAN codes should be an array',
                ],
                [
                    'field' => 'ean_codes',
                    'message' => 'This value should be of type array|\Countable.',
                ],
                [
                    'field' => 'ean_codes',
                    'message' => 'This value should be of type iterable.',
                ],
            ],
        ];

        yield 'ean codes containing invalid value' => [
            [
                'id' => $id,
                'name' => $name,
                'manufacturer' => $manufacturer,
                'categories' => $categories,
                'price' => $price,
                'ean_codes' => ['', 'ABB'],
            ],
            [
                [
                    'field' => 'ean_codes[0]',
                    'message' => 'This value should not be blank.',
                ],
                [
                    'field' => 'ean_codes[1]',
                    'message' => 'Ean codes should be numeric',
                ],
            ],
        ];

        yield 'missing categories' => [
            [
                'id' => $id,
                'name' => $name,
                'manufacturer' => $manufacturer,
                'price' => $price,
                'ean_codes' => $eanCodes,
            ],
            [
                [
                    'field' => 'categories',
                    'message' => 'This value should not be blank.',
                ],
            ],
        ];

        yield 'not array category' => [
            [
                'id' => $id,
                'name' => $name,
                'manufacturer' => $manufacturer,
                'categories' => '',
                'price' => $price,
                'ean_codes' => $eanCodes,
            ],
            [
                [
                    'field' => 'categories',
                    'message' => 'This value should not be blank.',
                ],
                [
                    'field' => 'categories',
                    'message' => 'Categories should be an array',
                ],
                [
                    'field' => 'categories',
                    'message' => 'This value should be of type array|\Countable.',
                ],
                [
                    'field' => 'categories',
                    'message' => 'This value should be of type iterable.',
                ],
            ],
        ];

        yield 'category containing invalid value' => [
            [
                'id' => $id,
                'name' => $name,
                'manufacturer' => $manufacturer,
                'categories' => [''],
                'price' => $price,
                'ean_codes' => $eanCodes,
            ],
            [
                [
                    'field' => 'categories[0]',
                    'message' => 'This value should not be blank.',
                ],
            ],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $repository = $this->container->get(ProductRepositoryInterface::class);
        $this->assertInstanceOf(ProductRepository::class, $repository);
        $this->repository = $repository;
    }

    public function test200Ok(): void
    {
        $this->createProduct(
            $this->repository,
            'name',
            'manuf',
            '89.90',
            ['categ'],
            ['759427']
        );

        $product = $this->repository->getAll()[0];

        $name = 'newName';
        $manufacturer = 'newMan';
        $price = '40.80';
        $categories = ['newCat', 'newerCat'];
        $eanCodes = ['238402384', '43820823'];
        $this->client->request(
            'PATCH',
            self::ENDPOINT,
            [
                'id' => $product['id'],
                'name' => $name,
                'manufacturer' => $manufacturer,
                'price' => $price,
                'categories' => $categories,
                'ean_codes' => $eanCodes,
            ]
        );

        self::assertResponseIsSuccessful();

        $this->assertSame(
            [
                'code' => 200,
                'message' => 'Product updates successfully.',
            ],
            $this->extractResponse()
        );

        $this->entityManager->clear();
        $updated = $this->repository->getById($product['id']);

        $this->assertSame(
            [
                'id' => $product['id'],
                'name' => $name,
                'manufacturer' => $manufacturer,
                'price' => $price,
                'categories' => $categories,
                'ean_codes' => $eanCodes,
            ],
            $updated
        );
    }

    public function test404NotFound(): void
    {
        $id = '2063509f-a4cd-431c-8667-23d8ad574ac8';
        $this->client->request(
            'PATCH',
            self::ENDPOINT,
            [
                'id' => $id,
                'name' => 'name',
                'manufacturer' => 'man',
                'price' => '89.90',
                'categories' => ['cat'],
                'ean_codes' => ['3423423'],
            ]
        );

        self::assertResponseStatusCodeSame(404);

        $this->assertSame(
            [
                'code' => 404,
                'message' => sprintf('A product with id %s could not be found', $id),
            ],
            $this->extractResponse()
        );
    }

    /**
     * @dataProvider badRequestProvider
     */
    public function test400BadRequest(array $payload, array $errors): void
    {
        $this->client->request(
            'PATCH',
            self::ENDPOINT,
            $payload
        );

        self::assertResponseStatusCodeSame(400);

        $this->assertSame(
            [
                'code' => 400,
                'message' => 'Request needs modification',
                'errors' => $errors,
            ],
            $this->extractResponse()
        );
    }
}
