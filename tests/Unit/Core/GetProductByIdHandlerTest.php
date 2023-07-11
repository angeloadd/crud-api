<?php

declare(strict_types=1);

namespace App\Tests\Unit\Core;

use App\Core\DeleteProduct\ProductNotFoundException;
use App\Core\GetProductById\GetProductByIdDTO;
use App\Core\GetProductById\GetProductByIdHandler;
use App\Repository\ProductRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class GetProductByIdHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $id = '8af95b06-19bf-46fc-ac34-43a588fed55d';
        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects(self::once())->method('getById')->with($id)->willReturn([
            'id' => $id,
            'name' => 'keyboard',
            'manufacturer' => 'manu',
            'categories' => ['cate'],
            'ean_codes' => ['382402834'],
        ]);

        (new GetProductByIdHandler($repository))->handle(new GetProductByIdDTO($id));
    }

    public function testHandleIfProductIsNotFound(): void
    {
        $id = '8af95b06-19bf-46fc-ac34-43a588fed55d';
        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects(self::once())->method('getById')->with($id)->willReturn(null);

        $this->expectException(ProductNotFoundException::class);
        (new GetProductByIdHandler($repository))->handle(new GetProductByIdDTO($id));
    }
}
