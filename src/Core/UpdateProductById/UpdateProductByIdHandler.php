<?php

declare(strict_types=1);

namespace App\Core\UpdateProductById;

use App\Core\DeleteProduct\ProductNotFoundException;
use App\Core\HandlerInterface;
use App\Core\UseCaseDTOInterface;
use App\Repository\ProductRepositoryInterface;

final class UpdateProductByIdHandler implements HandlerInterface
{
    public function __construct(private readonly ProductRepositoryInterface $repository)
    {
    }

    /**
     * @param UseCaseDTOInterface&UpdateProductByIdDTO $DTO
     */
    public function handle(UseCaseDTOInterface $DTO): ?array
    {
        $id = $DTO->id;
        $product = $this->repository->getById($id);

        if (!$product) {
            throw ProductNotFoundException::withId($id);
        }

        $this->repository->updateById(
            $id,
            $DTO->name ?? $product['name'],
            $DTO->manufacturer ?? $product['manufacturer'],
            $DTO->price ?? $product['price'],
            $DTO->categories ?? $product['categories'],
            $DTO->eanCodes ?? $product['ean_codes'],
        );

        return null;
    }
}
