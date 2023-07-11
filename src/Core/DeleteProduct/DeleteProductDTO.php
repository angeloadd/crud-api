<?php

declare(strict_types=1);

namespace App\Core\DeleteProduct;

use App\Core\UseCaseDTOInterface;

final class DeleteProductDTO implements UseCaseDTOInterface
{
    public function __construct(public readonly string $id)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
