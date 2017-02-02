<?php


use App\Category;

class CategoryTest extends TestCase
{
    function testCategory()
    {
        $categories = factory(Category::class, 3)->create();

        $this->assertEquals($categories->count(), 3);
    }
}