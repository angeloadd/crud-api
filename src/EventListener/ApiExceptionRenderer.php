<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\RequestValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class ApiExceptionRenderer
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        if (str_contains($request->getRequestUri(), 'api')) {
            $code = $exception->getCode();
            $statusCode = $code < 100 || $code >= 600 ? 500 : $code;

            $data = [
                'code' => $statusCode,
                'message' => $exception->getMessage(),
            ];

            if ($exception instanceof RequestValidationException) {
                $data['errors'] = $exception->getErrors();
            }

            $event->setResponse(new JsonResponse($data, $statusCode));
        }
    }
}
