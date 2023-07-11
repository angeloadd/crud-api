<?php

declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Core\HandlerInterface;
use App\Core\UseCaseDTOInterface;
use App\Presenter\ApiPresenterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/product')]
final class GetProductsAction extends AbstractController
{
    public function __construct(
        private readonly HandlerInterface $handler,
        private readonly ApiPresenterInterface $presenter,
    ) {
    }

    #[Route(name: 'get_products', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->presenter->present(
            200,
            'A list of all the products available',
            $this->handler->handle(
                new class() implements UseCaseDTOInterface {
                    public function toArray(): array
                    {
                        return [];
                    }
                }
            )
        );
    }
}
