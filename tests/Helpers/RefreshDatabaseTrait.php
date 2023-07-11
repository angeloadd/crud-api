<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

trait RefreshDatabaseTrait
{
    private function initDatabase(): void
    {
        $connection = $this->entityManager->getConnection();
        $schemaManager = $connection->createSchemaManager();
        $tables = $schemaManager->listTableNames();

        // You may need to use this in order to make it work
        // SET FOREIGN_KEY_CHECKS = 0;
        $connection->executeQuery(
            array_reduce(
                $tables,
                static fn (string $carry, string $table): string => $carry.'TRUNCATE '.$table.';',
                ''
            )
        );
    }

    public function closeDatabaseConnection(): void
    {
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
