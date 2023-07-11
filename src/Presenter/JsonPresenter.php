<?php

declare(strict_types=1);

namespace App\Presenter;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class JsonPresenter implements ApiPresenterInterface
{
    public function present(int $code = 200, string $message = 'OK', array $data = []): Response
    {
        $content = [
            'code' => $code,
            'message' => $message,
        ];

        if ([] !== $data) {
            $content['data'] = $data;
        }

        return new JsonResponse($content, $code);
    }
}
