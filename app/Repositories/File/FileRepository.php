<?php


namespace App\Repositories\File;

use App\File;
use App\Repositories\DbRepository;

/**
 * Class FileRepository
 * @package App\Repositories\File
 */
class FileRepository extends DbRepository
{

    /**
     * FileRepository constructor.
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->model = $file;
    }

}