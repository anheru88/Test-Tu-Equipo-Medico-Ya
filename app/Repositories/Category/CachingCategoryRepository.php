<?php


namespace App\Repositories\Category;

use Illuminate\Contracts\Cache\Repository as Cache;


/**
 * Class CachingCategoryRepository
 * @package App\Repositories\Category
 */
class CachingCategoryRepository implements ICategoryRepository
{
    /**
     * @var ICategoryRepository
     */
    private $categoryRepository;
    /**
     * @var Cache
     */
    private $cache;

    /**
     * CachingCategoryRepository constructor.
     * @param ICategoryRepository $categoryRepository
     * @param Cache $cache
     */
    public function __construct(ICategoryRepository $categoryRepository, Cache $cache)
    {
        $this->categoryRepository = $categoryRepository;
        $this->cache = $cache;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function checkIfExistByName($name)
    {
        return $this->categoryRepository->checkIfExistByName($name);
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->cache->remember('Category.all', 30, function () {
            return $this->categoryRepository->getAll();
        });
    }

    public function create($data)
    {
        $this->categoryRepository->create($data);
    }

    public function forgetCache()
    {
        $this->cache->forget('Category.all');
    }


    public function getbyName($name)
    {
        return $this->categoryRepository->getbyName($name);
    }
}