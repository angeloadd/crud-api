<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\Tests\Helpers\AssertOnDatabaseTrait;
use App\Tests\Helpers\RefreshDatabaseTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

abstract class ApiTestCase extends WebTestCase
{
    use RefreshDatabaseTrait;
    use AssertOnDatabaseTrait;

    protected readonly Container $container;

    protected ?EntityManagerInterface $entityManager;

    protected readonly KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();

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
