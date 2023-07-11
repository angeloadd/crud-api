<?php

declare(strict_types=1);

namespace App\Core;

interface HandlerInterface
{
    public function handle(UseCaseDTOInterface $DTO): ?array;
}
