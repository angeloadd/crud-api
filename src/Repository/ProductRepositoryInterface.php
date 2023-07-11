<?php

declare(strict_types=1);

namespace App\Repository;

interface ProductRepositoryInterface
{
    public function createProduct(
        string $name,
        string $manufacturer,
        string $price,
        array $categories,
        array $eanCodes
    ): void;

    public function deleteById(string $id): bool;

    public function getAll(): array;

    public function getById(string $id): ?array;

    public function updateById(
        string $id,
        string $name,
        string $manufacturer,
        string $price,
        array $categories,
        array $eanCodes
    ): bool;
}
