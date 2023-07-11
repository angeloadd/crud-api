<?php

declare(strict_types=1);

namespace App\Exception;

final class RequestValidationException extends \RuntimeException
{
    private array $errors;

    public function __construct(string $message, int $code, array $errors)
    {
        $this->errors = $errors;
        parent::__construct($message, $code, null);
    }

    public static function fromErrors(array $errors): self
    {
        return new self(
            'Request needs modification',
            400,
            $errors
        );
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
