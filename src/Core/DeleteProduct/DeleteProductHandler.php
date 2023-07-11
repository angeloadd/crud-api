<?php

declare(strict_types=1);

namespace App\Core\DeleteProduct;

use App\Core\HandlerInterface;
use App\Core\UseCaseDTOInterface;
use App\Repository\ProductRepositoryInterface;

final class DeleteProductHandler implements HandlerInterface
{
    public function __construct(private readonly ProductRepositoryInterface $repository)
    {
    }

    /**
     * @param UseCaseDTOInterface&DeleteProductDTO $DTO
     */
    public function handle(UseCaseDTOInterface $DTO): ?array
    {
        if (!$this->repository->deleteById($DTO->id)) {
            throw ProductNotFoundException::withId($DTO->id);
        }

        return null;
    }
}
