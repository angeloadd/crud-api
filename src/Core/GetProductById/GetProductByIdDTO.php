<?php

declare(strict_types=1);

namespace App\Core\GetProductById;

use App\Core\UseCaseDTOInterface;

final class GetProductByIdDTO implements UseCaseDTOInterface
{
    public function __construct(public readonly string $id)
    {
    }

    public function toArray(): array
    {
        return ['id' => $this->id];
    }
}
