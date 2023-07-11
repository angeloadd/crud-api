<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase
{
    public function testGetters(): void
    {
        $product = new Product();
        $name = 'Keyboard';
        $price = '89.90';
        $manufacturer = 'Apple';
        $categories = ['Apple'];
        $eanCodes = ['7439759'];
        $product->setName($name);
        $product->setManufacturer($manufacturer);
        $product->setPrice($price);
        $product->setCategories($categories);
        $product->setEanCodes($eanCodes);

        $this->assertSame($name, $product->getName());
        $this->assertSame($manufacturer, $product->getManufacturer());
        $this->assertSame($price, $product->getPrice());
        $this->assertSame($categories, $product->getCategories());
        $this->assertSame($eanCodes, $product->getEanCodes());
    }

    public function testToArray(): void
    {
        $product = new Product();
        $name = 'Keyboard';
        $price = '89.90';
        $manufacturer = 'Apple';
        $categories = ['Apple'];
        $eanCodes = ['7439759'];
        $product->setName($name);
        $product->setManufacturer($manufacturer);
        $product->setPrice($price);
        $product->setCategories($categories);
        $product->setEanCodes($eanCodes);

        $this->assertSame([
            'id' => '',
            'name' => $name,
            'manufacturer' => $manufacturer,
            'price' => $price,
            'categories' => $categories,
            'ean_codes' => $eanCodes,
        ], $product->toArray());
    }
}
