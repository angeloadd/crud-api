<?php

declare(strict_types=1);

namespace App\Tests\Unit\Core;

use App\Core\GetProducts\GetProductsHandler;
use App\Core\GetProducts\NoProductAvailableException;
use App\Core\UseCaseDTOInterface;
use App\Repository\ProductRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class GetProductsHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $name = 'Keyboard';
        $eanCodes = ['93249743'];
        $manufacturer = 'Apple';
        $price = '89.90';
        $name2 = 'Mouse';
        $eanCodes2 = ['4929743'];

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects(self::once())->method('getAll')->willReturn(
            [
                [
                    'id' => 'b60cb63c-8658-41fc-a15c-456fc8704565',
                    'name' => $name,
                    'manufacturer' => $manufacturer,
                    'price' => $price,
                    'categories' => [$manufacturer],
                    'ean_codes' => $eanCodes,
                ],
                [
                    'id' => '4acefc0e-f40f-41e1-bd4c-e47fb004493a',
                    'name' => $name2,
                    'manufacturer' => $manufacturer,
                    'price' => $price,
                    'categories' => [$manufacturer],
                    'ean_codes' => $eanCodes2,
                ],
            ]
        );
        (new GetProductsHandler($repository))->handle(
            new class() implements UseCaseDTOInterface {
                public function toArray(): array
                {
                    return [];
                }
            }
        );
    }

    public function testHandleIfNoProductAvailable(): void
    {
        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects(self::once())
            ->method('getAll')
            ->willReturn([]);

        $this->expectException(NoProductAvailableException::class);
        (new GetProductsHandler($repository))->handle(
            new class() implements UseCaseDTOInterface {
                public function toArray(): array
                {
                    return [];
                }
            }
        );
    }
}
