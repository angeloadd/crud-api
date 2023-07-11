<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use PHPUnit\Framework\Assert;

trait AssertOnDatabaseTrait
{
    protected function assertEntityOnDatabase(string $entityClass, string $searchBy, array $data): void
    {
        $entityRepository = $this->entityManager->getRepository($entityClass);

        $entity = $entityRepository->findOneBy([$searchBy => $data[$searchBy]]);

        if (!$entity instanceof $entityClass) {
            Assert::fail(sprintf('Entity %s could not be found in database', $entityClass));
        }

        $props = (new \ReflectionClass($entity))->getProperties();

        foreach ($props as $prop) {
            if (null === ($data[$prop->getName()] ?? null)) {
                continue;
            }
            Assert::assertSame($data[$prop->getName()], $prop->getValue($entity));
        }

        Assert::assertNotNull($entity->getId());
        Assert::assertTrue(uuid_is_valid((string) $entity->getId()));
        Assert::assertInstanceOf(\DateTimeImmutable::class, $entity->getCreatedAt());
        Assert::assertInstanceOf(\DateTimeImmutable::class, $entity->getUpdatedAt());
    }
}
