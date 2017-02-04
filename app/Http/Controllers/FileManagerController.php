<?php


namespace App\Http\Controllers;


use App\File;
use App\Image;
use App\Repositories\Category\ICategoryRepository;
use App\Repositories\File\FileRepository;
use App\Repositories\Product\ProductRepository;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;

/**
 * Class FileManagerController
 * @package App\Http\Controllers
 */
class FileManagerController extends Controller
{
    /**
     * @var FileRepository
     */
    private $fileRepository;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * FileManagerController constructor.
     * @param FileRepository $fileRepository
     * @param CategoryRepository|ICategoryRepository $categoryRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(
        FileRepository $fileRepository,
        ICategoryRepository $categoryRepository,
        ProductRepository $productRepository
    ) {
        $this->fileRepository = $fileRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }


    /**
     * Regresa la vista para subir el archivo.
     *
     * @return \Illuminate\View\View
     */
    public function show_file_upload_page()
    {
        return view('loadfile');
    }


    /**
     *  Metodo que procesa el archivo y la informacion suministrada por la pagina inicial.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function upload_file(Request $request)
    {

        /**
         * Validamos si existe el archivo en el request de los datos.
         * Si existe procedemos a procesar la informacion
         * Si no existe se regresa a la pagina principal mostrando el error, no se valida el archivo en el fron end.
         *
         */
        if ($request->hasFile('excelfile')) {

            /**
             * Guardamos la extension del archivo para verificar si es csv o xlsx
             */
            $file_extension = $request->file('excelfile')->getClientOriginalExtension();

            /**
             * Verficiamos si la extension del archivo es csv o xlsx, para poder proceder
             * si no es ninguna de las dos, regresamos a la pantalla inicial, y mostramos error.
             *
             */
            if ($file_extension == 'csv' or $file_extension == "xlsx" or $file_extension == "xls") {

                /**
                 * Guardamos el nombre del archivo con el timestamp del momento en que se crea el archivo
                 * mas el nombre original.
                 */
                $file_name = Carbon::now()->timestamp . '-' . $request->file('excelfile')->getClientOriginalName();

                /**
                 * Copiamos el archivo a la carpeta excel_files dentro de public
                 * Aunque el nombre del archivo se repita cada archivo se guardara de forma unica
                 */
                $request->file('excelfile')->move('excel_files/', $file_name);

                /**
                 * Creamos la informacion en el repositorio de File,
                 * la informacion a almacenar es la referente a la del archivo.
                 */
                $File = $this->set_file_attributes_and_create($request, $file_name);

                /**
                 * Creamos un array con el string de los datos,
                 * uno para la identificacion en la base de datos, y otro
                 * para la identificacion de los datos en las columnas del archivo
                 * de Excel o csv.
                 */
                $fields_database = explode(',', $File['fields_database']);
                $fields_file = explode(',', $File['fields_file']);

                $this->processCategories($File, $fields_database, $fields_file);

                $array_of_data = $this->proccessProducts($File, $fields_database, $fields_file);

                if (!$array_of_data[0]) {
                    return view('question', ['name' => $array_of_data[1], 'id' => $File->id]);
                }

                return redirect('/');
            }

        } else {

        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View|\Laravel\Lumen\Http\Redirector
     */
    public function question(Request $request)
    {
        $id = $request->id_field;

        $File = $this->fileRepository->getById($id);

        $select = $request->value;

        /**
         * Creamos un array con el string de los datos,
         * uno para la identificacion en la base de datos, y otro
         * para la identificacion de los datos en las columnas del archivo
         * de Excel o csv.
         */
        $fields_database = explode(',', $File['fields_database']);
        $fields_file = explode(',', $File['fields_file']);

        $array_of_data = $this->proccesProductOverride($File, $fields_database, $fields_file, $select);

        if (!$array_of_data[0]) {

            return view('question', ['name' => $array_of_data[1], 'id' => $File->id]);
        }

        return redirect('/');
    }

    /**
     *
     * Metodo para crear en la base de datos la informacion sobre el archivo
     * @param Request $request
     * @param $file_name
     * @internal param $dataFile
     * @return mixed
     */
    private function set_file_attributes_and_create(Request $request, $file_name)
    {
        $dataFile['name'] = $file_name;

        $dataFile['path_file'] = 'excel_files/' . $file_name;

        $fields_file = "";

        foreach ($request->fields as $field) {
            $fields_file .= $field . ',';
        }

        $dataFile['fields_file'] = $fields_file;

        $fields_database = "";

        foreach ($request->selects as $select) {
            $fields_database .= $select . ',';
        }

        $dataFile['fields_database'] = $fields_database;

        $file = $this->fileRepository->create($dataFile);

        return $this->fileRepository->getById($file->id);
    }


    /**
     * En este metodo se verifica si las categorias existen, o no,
     * si no existen se crean las nuevas.
     * @param File $File
     * @param $fields_database
     * @param $fields_file
     */
    private function processCategories(File $File, $fields_database, $fields_file)
    {

        /**
         * Creamos un arreglo de indices para indicar en que columna estan las categorias
         * del archivo en excel
         */
        $index_database_categories = [];
        $index = 0;

        /**
         * Guardamos todos los indices en el arreglo.
         */
        foreach ($fields_database as $field) {
            if ($field == 'category') {
                array_push($index_database_categories, $index);
            }
            $index++;
        }

        /**
         * Realizamos un ciclo para buscar las columnas de tipo categoria
         */
        for ($i = 0; $i < count($index_database_categories); $i++) {

            /**
             * Traemos el nombre de la columna en nuestro archivo Excel para
             * agregar las nuevas categorias a nuestro repositorio
             */
            $name = $fields_file[$index_database_categories[$i]];

            /**
             * Usamos la libreria de Excel, para traer la informacion
             * pasamos el nombre de nuestra columna para poder traer las nuevas
             * categorias
             */
            Excel::load($File->path_file, function ($reader) use ($name) {

                /**
                 * Agrupamos los datos de nuestro Excel, por categorias
                 */
                $results = $reader->all()->groupBy($name)->toArray();

                /**
                 * Creamos un array llamado keys, con la lista de las categorias
                 */
                $keys = array_keys($results);


                /**
                 * Hacemos un ciclo, para verificar si las categorias ya existen o no
                 */
                foreach ($keys as $key) {

                    /**
                     * Chequemos en nuestro repositorio si existe una categoria con ese nombre
                     */
                    $boolean = $this->categoryRepository->checkIfExistByName($key);

                    /**
                     * Si no existe la categoria procedemos a crearla.
                     */
                    if (!$boolean) {
                        $data['name'] = $key;

                        $this->categoryRepository->create($data);
                    }

                }

                /**
                 * Eliminamos el cache de todas las categorias
                 */
                $this->categoryRepository->forgetCache();
            });
        }

    }

    /**
     * @param File $File
     * @param $fields_database
     * @param $fields_file
     * @return
     */
    private function proccessProducts(File $File, $fields_database, $fields_file)
    {

        global $array_of_data;

        Excel::load($File->path_file, function ($reader) use ($File, $fields_database, $fields_file) {

            $results = $reader->get()->toArray();


            for ($i = 0; $i < count($results); $i++) {
                /**
                 * Busca el indice dentro del arreglo de la base de datos.
                 */
                $index_name = array_search('name', $fields_database);

                /**
                 * Guardamos el nombre de la columna pertenenciente al nombre del producto
                 */
                $name_colum = $fields_file[$index_name];

                /**
                 * Chequeamos en nuestro repositorio si existe algun producto con el mismo valor
                 */
                $name_value = $results[$i][$name_colum];

                /**
                 * Verificamos si existe el producto en la base de datos
                 */
                $bool = $this->productRepository->checkIfExistByName($name_value);

                if ($bool) {

                    $File->last_line = $i;

                    $File->save();

                    $GLOBALS['array_of_data'] = [false, $name_value];

                    return 0;
                } else {
                    $data = $this->setDataProduct($results, $i, $name_value, $fields_database, $fields_file);

                    $product = $this->productRepository->create($data);

                    $index_category = array_keys($fields_database, 'category');

                    $product->categories()->detach();

                    for ($j = 0; $j < count($index_category); $j++) {

                        $category_name = $results[$i][$fields_file[$index_category[$j]]];

                        $category = $this->categoryRepository->getbyName($category_name);

                        $product->categories()->attach($category->id);
                    }

                    $index_images = array_keys($fields_database, 'image');

                    for ($k = 0; $k < count($index_images); $k++) {
                        $image_url = $results[$i][$fields_file[$index_images[$k]]];

                        $image = new Image();
                        $image->external_url = $image_url;

                        $product->images()->save($image);
                    }
                }


            }

            $GLOBALS['array_of_data'] = [true];

        });

        return $array_of_data;
    }

    /**
     * @param File $File
     * @param $fields_database
     * @param $fields_file
     * @param $option
     * @return mixed
     */
    private function proccesProductOverride(File $File, $fields_database, $fields_file, $option)
    {
        global $array_of_data;

        Excel::load($File->path_file, function ($reader) use ($File, $fields_database, $fields_file, $option) {
            $results = $reader->get()->toArray();

            $index = $File->last_line;

            for ($i = $index; $i < count($results); $i++) {
                /**
                 * Busca el indice dentro del arreglo de la base de datos.
                 */
                $index_name = array_search('name', $fields_database);

                /**
                 * Guardamos el nombre de la columna pertenenciente al nombre del producto
                 */
                $name_colum = $fields_file[$index_name];

                /**
                 * Chequeamos en nuestro repositorio si existe algun producto con el mismo valor
                 */
                $name_value = $results[$i][$name_colum];

                /**
                 * Verificamos si existe el producto en la base de datos
                 */
                $bool = $this->productRepository->checkIfExistByName($name_value);

                if ($bool) {
                    switch ($option) {
                        case  0:
                            $data = $this->setDataProduct($results, $i, $name_value, $fields_database, $fields_file);

                            $product = $this->productRepository->overrideByName($data);

                            $index_category = array_keys($fields_database, 'category');

                            $product->categories()->detach();

                            for ($j = 0; $j < count($index_category); $j++) {

                                $category_name = $results[$i][$fields_file[$index_category[$j]]];

                                $category = $this->categoryRepository->getbyName($category_name);

                                $product->categories()->attach($category->id);
                            }

                            $index_images = array_keys($fields_database, 'image');

                            for ($k = 0; $k < count($index_images); $k++) {
                                $image_url = $results[$i][$fields_file[$index_images[$k]]];


                                $image = new Image();
                                $image->external_url = $image_url;

                                $product->images()->save($image);
                            }
                            $option = 4;
                            break;
                        case 1:
                            $option = 4;
                            break;
                        case 2:
                            $data = $this->setDataProduct($results, $i, $name_value, $fields_database, $fields_file);

                            $product = $this->productRepository->overrideByName($data);

                            $index_category = array_keys($fields_database, 'category');

                            $product->categories()->detach();

                            for ($j = 0; $j < count($index_category); $j++) {

                                $category_name = $results[$i][$fields_file[$index_category[$j]]];

                                $category = $this->categoryRepository->getbyName($category_name);

                                $product->categories()->attach($category->id);
                            }

                            $index_images = array_keys($fields_database, 'image');

                            for ($k = 0; $k < count($index_images); $k++) {
                                $image_url = $results[$i][$fields_file[$index_images[$k]]];


                                $image = new Image();
                                $image->external_url = $image_url;

                                $product->images()->save($image);
                            }

                            break;
                        case 3:
                            break;
                        case 4:
                            $File->last_line = $i;

                            $File->save();

                            $GLOBALS['array_of_data'] = [false, $name_value];

                            return 0;
                    }
                } else {
                    $data = $this->setDataProduct($results, $i, $name_value, $fields_database, $fields_file);

                    $product = $this->productRepository->create($data);

                    $index_category = array_keys($fields_database, 'category');

                    $product->categories()->detach();

                    for ($j = 0; $j < count($index_category); $j++) {

                        $category_name = $results[$i][$fields_file[$index_category[$j]]];

                        $category = $this->categoryRepository->getbyName($category_name);

                        $product->categories()->attach($category->id);
                    }

                    $index_images = array_keys($fields_database, 'image');

                    for ($k = 0; $k < count($index_images); $k++) {
                        $image_url = $results[$i][$fields_file[$index_images[$k]]];

                        $image = new Image();
                        $image->external_url = $image_url;

                        $product->images()->save($image);
                    }
                }

                $GLOBALS['array_of_data'] = [true];

            }
        });

        return $array_of_data;
    }

    /**
     * @param $results
     * @param $i
     * @param $name_value
     * @param $fields_database
     * @param $fields_file
     * @return array
     */
    private function setDataProduct($results, $i, $name_value, $fields_database, $fields_file)
    {
        $data = [];

        $data['name'] = $name_value;

        $index_file_url = array_search('file', $fields_database);
        $index_description = array_search('description', $fields_database);
        $index_price = array_search('price', $fields_database);

        if ($index_file_url != false) {
            $file_url_colum = $fields_file[$index_file_url];
            $data['file_url'] = $results[$i][$file_url_colum];
        }

        if ($index_description != false) {
            $description_colum = $fields_file[$index_description];
            $data['description'] = $results[$i][$description_colum];
        }

        if ($index_price != false) {
            $price_colum = $fields_file[$index_price];
            $data['price'] = $results[$i][$price_colum];
            return $data;
        }

        return $data;
    }
}