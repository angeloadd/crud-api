<?php

declare(strict_types=1);

namespace App\Core\CreateProduct;

use App\Core\UseCaseDTOInterface;

final class CreateProductDTO implements UseCaseDTOInterface
{
    public function __construct(
        public readonly string $name,
        public readonly string $manufacturer,
        public readonly string $price,
        public readonly array $categories,
        public readonly array $eanCodes
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'manufacturer' => $this->manufacturer,
            'price' => $this->price,
            'categories' => $this->categories,
            'eanCodes' => $this->eanCodes,
        ];
    }
}
