<?php


use App\Category;
use App\Product;

/**
 * Class ProductTest
 */
class ProductTest extends TestCase
{

    /**
     * Este test verifica la clase de producto, utilizando la fabrica de datos.
     */
    public function testProductClass()
    {
        $products = factory(Product::class, 3)->create();

        $this->assertEquals($products->count(), 3);
    }

    /**
     * Este test agrega dos categorias al producto, y despues verifica que existan en la tabla pivot.
     *
     * @test
     */
    public function a_product_can_attach_new_category()
    {
        $product = factory(Product::class)->create();

        $categories = factory(Category::class, 2)->create();

        $product->categories()->attach($categories[0]->id);

        $product->categories()->attach($categories[1]->id);

        $this->assertEquals(2, $product->categories()->count());
    }
}