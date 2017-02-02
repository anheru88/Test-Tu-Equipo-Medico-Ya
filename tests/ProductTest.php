<?php


use App\Product;

/**
 * Class ProductTest
 */
class ProductTest extends TestCase
{


    function testProductClass()
    {
        $products = factory(Product::class, 3)->create();

        $this->assertEquals($products->count(), 3);
    }
}