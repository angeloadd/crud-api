<?php

declare(strict_types=1);

namespace App\Tests\Unit\Presenter;

use App\Presenter\JsonPresenter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

final class JsonPresenterTest extends TestCase
{
    public function testWithData(): void
    {
        $presenter = new JsonPresenter();

        $data = [
            'content' => [
                'prop1' => 'value',
                'prop2' => 'value',
            ],
        ];
        $response = $presenter->present(201, 'Ciao', $data);

        $this->assertEquals(
            new JsonResponse(
                [
                    'code' => 201,
                    'message' => 'Ciao',
                    'data' => $data,
                ], 201,
            ),
            $response
        );
    }
}
