<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventListener;

use App\EventListener\ApiExceptionRenderer;
use App\Exception\RequestValidationException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class ApiExceptionRendererTest extends TestCase
{
    private const ERRORS = [
        [
            'field' => 'name',
            'message' => 'Nooooooooo',
        ],
    ];

    private MockObject&HttpKernelInterface $kernel;

    private MockObject&Request $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->kernel = $this->createMock(HttpKernelInterface::class);
        $this->request = $this->createMock(Request::class);
    }

    public function testOnKernelException(): void
    {
        $exception = RequestValidationException::fromErrors(self::ERRORS);
        $this->request->expects(self::once())
            ->method('getRequestUri')
            ->willReturn('api/ciao');

        $event = new ExceptionEvent(
            $this->kernel,
            $this->request,
            0,
            $exception
        );

        (new ApiExceptionRenderer())->onKernelException($event);

        $this->assertEquals(
            $event->getResponse(),
            new JsonResponse(
                [
                    'code' => 400,
                    'message' => $exception->getMessage(),
                    'errors' => self::ERRORS,
                ],
                400
            )
        );
    }

    /**
     * @dataProvider exceptionProvider
     */
    public function testExceptionNotFromValidation(\Exception $exception, int $code): void
    {
        $this->request->expects(self::once())
            ->method('getRequestUri')
            ->willReturn('api/ciao');

        $event = new ExceptionEvent(
            $this->kernel,
            $this->request,
            0,
            $exception
        );

        (new ApiExceptionRenderer())->onKernelException($event);

        $this->assertEquals(
            $event->getResponse(),
            new JsonResponse(
                [
                    'code' => $code,
                    'message' => $exception->getMessage(),
                ],
                $code
            )
        );
    }

    public function testItSkipsIfNotApi(): void
    {
        $this->request->expects(self::once())
            ->method('getRequestUri')
            ->willReturn('ciao');

        $event = new ExceptionEvent(
            $this->kernel,
            $this->request,
            0,
            new \Exception('Ciao', 0)
        );

        (new ApiExceptionRenderer())->onKernelException($event);

        $this->assertNull($event->getResponse());
    }

    public static function exceptionProvider(): \Generator
    {
        yield 'valid code exception' => [
            new \Exception('Ciao', 400),
            400,
        ];

        yield 'exception with code less then 100' => [
            new \Exception('Ciao', 99),
            500,
        ];

        yield 'exception with code more then 599' => [
            new \Exception('Ciao', 600),
            500,
        ];
    }
}
