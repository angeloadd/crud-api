<?php

declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Core\DeleteProduct\DeleteProductDTO;
use App\Core\HandlerInterface;
use App\Presenter\ApiPresenterInterface;
use App\Request\v1\DeleteProductRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/product')]
final class DeleteProductAction extends AbstractController
{
    public function __construct(
        private readonly HandlerInterface $handler,
        private readonly ApiPresenterInterface $apiPresenter
    ) {
    }

    #[Route(name: 'delete_product', methods: ['DELETE'])]
    public function __invoke(DeleteProductRequest $request): Response
    {
        $this->handler->handle(new DeleteProductDTO($request->id()));

        return $this->apiPresenter->present(200, 'Product deleted successfully.');
    }
}
