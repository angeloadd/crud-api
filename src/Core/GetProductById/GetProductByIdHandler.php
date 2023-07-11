<?php

declare(strict_types=1);

namespace App\Core\GetProductById;

use App\Core\DeleteProduct\ProductNotFoundException;
use App\Core\HandlerInterface;
use App\Core\UseCaseDTOInterface;
use App\Repository\ProductRepositoryInterface;

final class GetProductByIdHandler implements HandlerInterface
{
    public function __construct(private readonly ProductRepositoryInterface $repository)
    {
    }

    /**
     * @param UseCaseDTOInterface&GetProductByIdDTO $DTO
     */
    public function handle(UseCaseDTOInterface $DTO): ?array
    {
        $product = $this->repository->getById($DTO->id);

        if (empty($product)) {
            throw ProductNotFoundException::withId($DTO->id);
        }

        return $product;
    }
}
