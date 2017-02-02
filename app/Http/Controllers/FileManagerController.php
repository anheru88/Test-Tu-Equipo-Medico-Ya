<?php


namespace App\Http\Controllers;


class FileManagerController extends Controller
{
    public function show_file_upload_page()
    {
        return view('loadfile');
    }
}