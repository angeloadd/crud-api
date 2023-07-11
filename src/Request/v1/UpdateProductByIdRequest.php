<?php

declare(strict_types=1);

namespace App\Request\v1;

use App\Request\AbstractRequest;

final class UpdateProductByIdRequest extends AbstractRequest
{
    protected mixed $id = null;
    protected mixed $name = null;
    protected mixed $manufacturer = null;
    protected mixed $price = null;
    protected mixed $categories = null;
    protected mixed $eanCodes = null;

    public function id(): string
    {
        return $this->id;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function manufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function price(): ?string
    {
        return $this->price;
    }

    public function categories(): ?array
    {
        return $this->categories;
    }

    public function eanCodes(): ?array
    {
        return $this->eanCodes;
    }
}
