<?php

declare(strict_types=1);

namespace App\Core\GetProducts;

final class NoProductAvailableException extends \RuntimeException
{
    public static function getInstance(): self
    {
        return new self(
            'At the moment there are no products available. Please add a product.',
            404
        );
    }
}
