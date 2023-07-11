<?php

declare(strict_types=1);

namespace App\Core\UpdateProductById;

use App\Core\UseCaseDTOInterface;

final class UpdateProductByIdDTO implements UseCaseDTOInterface
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $name,
        public readonly ?string $manufacturer,
        public readonly ?string $price,
        public readonly ?array $categories,
        public readonly ?array $eanCodes,
    ) {
    }

    public function toArray(): array
    {
        return [];
    }
}
