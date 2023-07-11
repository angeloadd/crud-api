<?php

declare(strict_types=1);

namespace App\Core\DeleteProduct;

final class ProductNotFoundException extends \RuntimeException
{
    public static function withId(string $id): self
    {
        return new self(
            sprintf('A product with id %s could not be found', $id),
            404
        );
    }
}
