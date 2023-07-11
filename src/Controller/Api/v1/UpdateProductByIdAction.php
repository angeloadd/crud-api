<?php

declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Core\HandlerInterface;
use App\Core\UpdateProductById\UpdateProductByIdDTO;
use App\Presenter\ApiPresenterInterface;
use App\Request\v1\UpdateProductByIdRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/product')]
final class UpdateProductByIdAction extends AbstractController
{
    public function __construct(
        private readonly HandlerInterface $handler,
        private readonly ApiPresenterInterface $apiPresenter,
    ) {
    }

    #[Route(name: 'update_product', methods: ['PATCH'])]
    public function __invoke(UpdateProductByIdRequest $request): Response
    {
        $this->handler->handle(
            new UpdateProductByIdDTO(
                $request->id(),
                $request->name(),
                $request->manufacturer(),
                $request->price(),
                $request->categories(),
                $request->eanCodes(),
            )
        );

        return $this->apiPresenter->present(200, 'Product updates successfully.');
    }
}
