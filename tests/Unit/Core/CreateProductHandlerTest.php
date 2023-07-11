<?php

declare(strict_types=1);

namespace App\Tests\Unit\Core;

use App\Core\CreateProduct\CreateProductDTO;
use App\Core\CreateProduct\CreateProductHandler;
use App\Repository\ProductRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class CreateProductHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $repository = $this->createMock(ProductRepositoryInterface::class);
        $name = 'Ciao';
        $manufacturer = 'Apple';
        $price = '89.90';
        $eanCodes = ['032842'];
        $repository->expects(self::once())
            ->method('createProduct')
            ->with(
                $name,
                $manufacturer,
                $price,
                [$manufacturer],
                $eanCodes
            );
        (new CreateProductHandler($repository))->handle(
            new CreateProductDTO(
                $name,
                $manufacturer,
                $price,
                [$manufacturer],
                $eanCodes
            )
        );
    }
}
