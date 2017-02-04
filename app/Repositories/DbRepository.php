<?php

namespace App\Repositories;


/**
 * Class DbRepository
 * @package App\Repositories
 */
abstract class DbRepository
{

    /**
     * Eloquent Model
     */
    protected $model;

    /**
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Fetch a record by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->model->find($id);
    }


    /**
     * Create new field using form data
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return $this->model->create($data);
    }

    /**
     * Delete field using id
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $model = $this->getById($id);

        if ($model) {
            $model->delete();

            return true;
        }

        return false;
    }


    /**
     * Update new field using id and form data
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $model = $this->getById($id);

        if ($model) {
            $model->update($data);
        }

        return $model;
    }


}