<?php

declare(strict_types=1);

namespace App\Core\GetProducts;

use App\Core\HandlerInterface;
use App\Core\UseCaseDTOInterface;
use App\Repository\ProductRepositoryInterface;

final class GetProductsHandler implements HandlerInterface
{
    public function __construct(private readonly ProductRepositoryInterface $productRepository)
    {
    }

    public function handle(UseCaseDTOInterface $DTO): ?array
    {
        $products = $this->productRepository->getAll();

        if ([] === $products) {
            throw NoProductAvailableException::getInstance();
        }

        return $products;
    }
}
