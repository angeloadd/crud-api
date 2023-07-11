<?php

declare(strict_types=1);

namespace App\Request\v1;

use App\Request\AbstractRequest;

final class GetProductByIdRequest extends AbstractRequest
{
    protected mixed $id = null;

    public function id(): string
    {
        return $this->id;
    }
}
