<?php


use App\Category;
use App\Product;

/**
 * Class CategoryTest
 */
class CategoryTest extends TestCase
{
    /**
     *
     */
    public function testCategory()
    {
        $categories = factory(Category::class, 3)->create();

        $this->assertEquals($categories->count(), 3);
    }

    /**
     * Este test agrega una categoria a dos productos diferentes, y despues verifica que existan en la tabla pivot.
     * 
     * @test
     */
    public function a_product_can_attach_new_category()
    {
        $category = factory(Category::class)->create();

        $products = factory(Product::class, 2)->create();

        $category->products()->attach($products[0]->id);

        $category->products()->attach($products[1]->id);

        $this->assertEquals(2, $category->products()->count());
    }
}