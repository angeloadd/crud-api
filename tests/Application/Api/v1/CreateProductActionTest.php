<?php

declare(strict_types=1);

namespace App\Tests\Application\Api\v1;

use App\Entity\Product;
use App\Tests\Application\ApiTestCase;
use App\Tests\Helpers\ExtractResponseTrait;

final class CreateProductActionTest extends ApiTestCase
{
    use ExtractResponseTrait;

    public const ENDOPOINT = '/api/v1/product';

    public function badRequestProvider(): \Generator
    {
        $name = 'Keyboard';
        $manufacturer = 'Apple';
        $price = '89.90';
        $categories = ['Elec', 'Apple'];
        $eanCodes = ['0324707430', '084302840'];

        yield 'missing name' => [
            [
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

    public function test201Created(): void
    {
        $name = 'Keyboard';
        $manufacturer = 'Apple';
        $price = '89.90';
        $categories = ['Elec', 'Apple'];
        $eanCodes = ['0324707430', '084302840'];

        $this->client->request('POST', self::ENDOPOINT, [
            'name' => $name,
            'manufacturer' => $manufacturer,
            'price' => $price,
            'categories' => $categories,
            'ean_codes' => $eanCodes,
        ]);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(201);

        $this->assertSame(
            [
                'code' => 201,
                'message' => 'Product was created successfully',
            ],
            $this->extractResponse()
        );

        $this->assertEntityOnDatabase(
            Product::class,
            'name',
            [
                'name' => $name,
                'manufacturer' => $manufacturer,
                'categories' => $categories,
                'eanCodes' => $eanCodes,
                'price' => $price,
            ]
        );
    }

    /**
     * @dataProvider badRequestProvider
     */
    public function test400BadRequest(array $payload, array $errors): void
    {
        $this->client->request('POST', self::ENDOPOINT, $payload);

        self::assertResponseStatusCodeSame(400);

        $this->assertSame([
            'code' => 400,
            'message' => 'Request needs modification',
            'errors' => $errors,
        ], $this->extractResponse());
    }
}
