<?php

declare(strict_types=1);

namespace App\Presenter;

use Symfony\Component\HttpFoundation\Response;

interface ApiPresenterInterface
{
    public function present(int $code = 200, string $message = 'OK', array $data = []): Response;
}
