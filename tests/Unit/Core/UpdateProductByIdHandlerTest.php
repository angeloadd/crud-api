<?php

declare(strict_types=1);

namespace App\Tests\Unit\Core;

use App\Core\DeleteProduct\ProductNotFoundException;
use App\Core\UpdateProductById\UpdateProductByIdDTO;
use App\Core\UpdateProductById\UpdateProductByIdHandler;
use App\Repository\ProductRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class UpdateProductByIdHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $repository = $this->createMock(ProductRepositoryInterface::class);
        $id = 'cbcf48f0-88d0-4cc8-905b-1374e1a761ac';
        $name = 'newName';
        $manufacturer = 'newManufacturer';
        $price = '60.90';
        $categories = ['newCat'];
        $eanCodes = ['2394723497'];
        $repository->expects(self::once())
            ->method('getById')
            ->with($id)
            ->willReturn([
                'id' => $id,
                'name' => 'name',
                'manufacturer' => 'manufacturer',
                'price' => '89.90',
                'categories' => ['cat'],
                'ean_codes' => ['237742'],
            ]);

        $repository->expects(self::once())
            ->method('updateById')
            ->with($id, $name, $manufacturer, $price, $categories, $eanCodes);

        (new UpdateProductByIdHandler($repository))->handle(
            new UpdateProductByIdDTO(
                $id,
                $name,
                $manufacturer,
                $price,
                $categories,
                $eanCodes
            )
        );
    }

    public function testHandleWhenProductIsNotFound(): void
    {
        $id = 'cbcf48f0-88d0-4cc8-905b-1374e1a761ac';
        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects(self::once())
            ->method('getById')
            ->with($id)
            ->willReturn(null);

        $this->expectException(ProductNotFoundException::class);

        (new UpdateProductByIdHandler($repository))->handle(
            new UpdateProductByIdDTO(
                $id,
                'name',
                'manufacturer',
                '89.90',
                ['cat'],
                ['38485']
            )
        );
    }
}
