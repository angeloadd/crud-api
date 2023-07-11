<?php

declare(strict_types=1);

namespace App\Tests\Unit\Core;

use App\Core\DeleteProduct\DeleteProductDTO;
use App\Core\DeleteProduct\DeleteProductHandler;
use App\Core\DeleteProduct\ProductNotFoundException;
use App\Repository\ProductRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class DeleteProductHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $id = '02059e1d-6z743-417e-8ab4-2ecca946bcbe';
        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects(self::once())->method('deleteById')->with($id)->willReturn(true);

        (new DeleteProductHandler($repository))->handle(new DeleteProductDTO($id));
    }

    public function testHandleWhenProductIsNotFound(): void
    {
        $id = '02059e1d-6z743-417e-8ab4-2ecca946bcbe';
        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects(self::once())->method('deleteById')->with($id)->willReturn(false);

        $this->expectException(ProductNotFoundException::class);
        (new DeleteProductHandler($repository))->handle(new DeleteProductDTO($id));
    }
}
