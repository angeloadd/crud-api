<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Tests\Helpers\AssertOnDatabaseTrait;
use App\Tests\Helpers\RefreshDatabaseTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;

abstract class IntegrationTestCase extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use AssertOnDatabaseTrait;

    protected readonly Container $container;

    protected ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $this->container = self::getContainer();

        $this->entityManager = $this->container->get('doctrine')->getManager();

        $this->initDatabase();
    }

    protected function tearDown(): void
    {
        $this->closeDatabaseConnection();
        parent::tearDown();
    }
}
