<?php

declare(strict_types=1);

namespace App\Core\CreateProduct;

use App\Core\HandlerInterface;
use App\Core\UseCaseDTOInterface;
use App\Repository\ProductRepositoryInterface;

final class CreateProductHandler implements HandlerInterface
{
    public function __construct(private readonly ProductRepositoryInterface $productRepository)
    {
    }

    public function handle(UseCaseDTOInterface $DTO): ?array
    {
        $this->productRepository->createProduct(...$DTO->toArray());

        return null;
    }
}
