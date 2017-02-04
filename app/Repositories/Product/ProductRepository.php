<?php

namespace App\Repositories\Product;

use App\Product;
use App\Repositories\DbRepository;

class ProductRepository extends DbRepository
{
    /**
     * ProductRepository constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    public function checkIfExistByName($name)
    {
        $product = $this->model->whereName($name)->get();

        if (count($product) < 1) {
            return false;
        }

        return true;
    }

    public function overrideByName($name, $data)
    {
        $product = $this->model->whereName($name)->first();

        $product = $this->getById($product->id);

        if (isset($data['file_url'])) {
            $product->file_url = $data['file_url'];
        }

        if (isset($data['description'])) {
            $product->description = $data['description'];
        }

        if (isset($data['price'])) {
            $product->price = $data['price'];
        }

        $product->save();

        return $product;
    }
}