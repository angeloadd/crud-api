<?php

declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Core\CreateProduct\CreateProductDTO;
use App\Core\HandlerInterface;
use App\Presenter\ApiPresenterInterface;
use App\Request\v1\CreateProductRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/product')]
final class CreateProductAction extends AbstractController
{
    public function __construct(
        private readonly HandlerInterface $handler,
        private readonly ApiPresenterInterface $presenter,
    ) {
    }

    #[Route(name: 'create_product', methods: ['POST'])]
    public function __invoke(CreateProductRequest $request): Response
    {
        $this->handler->handle(
            new CreateProductDTO(
                $request->name(),
                $request->manufacturer(),
                $request->price(),
                $request->categories(),
                $request->eanCodes()
            )
        );

        return $this->presenter->present(201, 'Product was created successfully');
    }
}
