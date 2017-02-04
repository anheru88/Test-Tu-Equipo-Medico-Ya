<?php

namespace App\Repositories\Category;


/**
 * Interface ICategoryRepository
 * @package App\Repositories\Category
 */
interface ICategoryRepository
{

    /**
     * @return mixed
     */
    public function getAll();

    /**
     * @param $name
     * @return mixed
     */
    public function checkIfExistByName($name);

    public function create($data);

    public function forgetCache();

    public function getbyName($name);
}