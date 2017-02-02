<?php


namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class FileManagerController
 * @package App\Http\Controllers
 */
class FileManagerController extends Controller
{


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
     *
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

            $file_extension = $request->file('excelfile')->getClientOriginalExtension();

            $file_name = Carbon::now()->timestamp . '-' . $request->file('excelfile')->getClientOriginalName();

            if ($file_extension == 'csv' or $file_extension == "xlsx") {
                $request->file('excelfile')->move('excel_files/', $file_name);
                return redirect('/');
            }
        } else {

        }
    }
}