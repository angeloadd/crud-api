<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

trait ExtractResponseTrait
{
    public function extractResponse(): array
    {
        return json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }
}
