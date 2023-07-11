<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use App\Repository\ProductRepository;

trait CreateProductTrait
{
    private function createProduct(
        ProductRepository $repository,
        string $name,
        string $manufacturer,
        string $price,
        array $categories,
        array $eanCodes
    ): void {
        $repository->createProduct(
            $name,
            $manufacturer,
            $price,
            $categories,
            $eanCodes
        );
    }
}
