<?php


namespace App\Repositories\Category;


use App\Category;
use App\Repositories\DbRepository;

class CategoryRepository extends DbRepository implements ICategoryRepository
{


    /**
     * CategoryRepository constructor.
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->model = $category;
    }


    public function checkIfExistByName($name)
    {
        $categories = $this->getAll();


        foreach ($categories as $category) {
            if ($category->name == $name) {
                return true;
            }
        }

        return false;

    }

    public function resetCache()
    {
        // TODO: Implement resetCache() method.
    }

    public function forgetCache()
    {
        // TODO: Implement forgetCache() method.
    }


    public function getbyName($name)
    {
        $category = $this->model->whereName($name)->first();

        return $this->getById($category->id);
    }
}