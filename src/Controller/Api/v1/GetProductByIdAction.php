<?php

declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Core\GetProductById\GetProductByIdDTO;
use App\Core\HandlerInterface;
use App\Presenter\ApiPresenterInterface;
use App\Request\v1\GetProductByIdRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetProductByIdAction extends AbstractController
{
    public function __construct(
        private readonly HandlerInterface $handler,
        private readonly ApiPresenterInterface $apiPresenter
    ) {
    }

    #[Route('/api/v1/product/{id}', name: 'show_product', methods: ['GET'])]
    public function __invoke(GetProductByIdRequest $request): Response
    {
        return $this->apiPresenter->present(
            200,
            'A product with the id provided',
            $this->handler->handle(new GetProductByIdDTO($request->id()))
        );
    }
}
