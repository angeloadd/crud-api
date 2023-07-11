<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class ProductRepository implements ProductRepositoryInterface
{
    private readonly EntityRepository $repository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Product::class);
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    public function createProduct(
        string $name,
        string $manufacturer,
        string $price,
        array $categories,
        array $eanCodes
    ): void {
        $product = new Product();

        $product->setName($name);
        $product->setEanCodes($eanCodes);
        $product->setCategories($categories);
        $product->setManufacturer($manufacturer);
        $product->setPrice($price);

        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function getAll(): array
    {
        return array_map(
            static fn (Product $product): array => $product->toArray(),
            $this->repository->findAll()
        );
    }

    public function deleteById(string $id): bool
    {
        return (bool) $this->repository->createQueryBuilder('p')
            ->delete()
            ->where('p.id = :product_id')
            ->setParameter('product_id', $id)
            ->getQuery()
            ->execute();
    }

    public function getById(string $id): ?array
    {
        return $this->repository->find($id)?->toArray();
    }

    public function updateById(
        string $id,
        string $name,
        string $manufacturer,
        string $price,
        array $categories,
        array $eanCodes
    ): bool {
        $categoriesToSimpleArray = Type::getType(Types::SIMPLE_ARRAY)->convertToDatabaseValue(
            $categories,
            $this->entityManager->getConnection()->getDatabasePlatform()
        );
        $eanCodesToSimpleArray = Type::getType(Types::SIMPLE_ARRAY)->convertToDatabaseValue(
            $eanCodes,
            $this->entityManager->getConnection()->getDatabasePlatform()
        );

        return (bool) $this->repository->createQueryBuilder('p')
            ->update()
            ->set('p.name', ':name')
            ->set('p.manufacturer', ':manufacturer')
            ->set('p.price', ':price')
            ->set('p.categories', ':categories')
            ->set('p.ean_codes', ':ean_codes')
            ->where('p.id=:product_id')
            ->setParameters([
                'name' => $name,
                'manufacturer' => $manufacturer,
                'price' => $price,
                'categories' => $categoriesToSimpleArray,
                'ean_codes' => $eanCodesToSimpleArray,
                'product_id' => $id,
            ])->getQuery()
            ->execute();
    }
}
