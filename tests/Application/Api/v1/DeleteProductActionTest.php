<?php

declare(strict_types=1);

namespace App\Tests\Application\Api\v1;

use App\Repository\ProductRepository;
use App\Repository\ProductRepositoryInterface;
use App\Tests\Application\ApiTestCase;
use App\Tests\Helpers\CreateProductTrait;
use App\Tests\Helpers\ExtractResponseTrait;

final class DeleteProductActionTest extends ApiTestCase
{
    use CreateProductTrait;
    use ExtractResponseTrait;

    public const ENDPOINT = '/api/v1/product';

    public function badRequestProvider(): \Generator
    {
        yield 'missing id' => [
            [
                'not' => 'valid',
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
            ],
            [
                [
                    'field' => 'id',
                    'message' => 'This is not a valid UUID.',
                ],
            ],
        ];
    }

    public function test200Ok(): void
    {
        $repository = $this->container->get(ProductRepositoryInterface::class);
        $this->assertInstanceOf(ProductRepository::class, $repository);
        $this->createProduct(
            $repository,
            'name',
            'manufacturer',
            '89.90',
            ['Cat'],
            ['483842']
        );

        $id = $repository->getAll()[0]['id'];

        $this->client->request(
            'DELETE',
            self::ENDPOINT,
            [
                'id' => $id,
            ]
        );

        self::assertResponseIsSuccessful();
        $this->assertSame(
            [
                'code' => 200,
                'message' => 'Product deleted successfully.',
            ],
            $this->extractResponse()
        );
    }

    public function test404NotFound(): void
    {
        $id = 'd424d4dc-222a-46b7-bf12-32497a0e577d';
        $this->client->request(
            'DELETE',
            self::ENDPOINT,
            [
                'id' => $id,
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
            'DELETE',
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
