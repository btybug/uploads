<?php
/**
 * Copyright (c) 2017.
 * *
 *  * Created by PhpStorm.
 *  * User: Edo
 *  * Date: 10/3/2016
 *  * Time: 10:44 PM
 *
 */

namespace App\Modules\Uploads\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Resources\Models\Files\FilesBB;
use App\Modules\Resources\Models\Files\FileUpload;
use Datatables;
use File,View;
use Illuminate\Http\Request;


class FilesController extends Controller
{
    public $types;
    public $file_upload;

    public function __construct ()
    {
        $this->file_upload = new FileUpload();
        $this->types = @json_decode(File::get(config('paths.files_path') . 'configTypes.json'), 1)['types'];
    }

    public function getIndex ()
    {
        $files = FilesBB::getFilesTypes('csv');
        $types = $this->types;

        return view('uploads::assets.files.list', compact(['types', 'files']));
    }

    public function postFilesWithType (Request $request)
    {
        $main_type = $request->get('main_type');
        $files = FilesBB::getFilesTypes($main_type);
        $html = View::make('uploads::assets.files._partials.list_cube', compact(['files']))->render();

        return \Response::json(['html' => $html, 'error' => false]);
    }

    public function postUpload (Request $request)
    {
        return $this->file_upload->upload($request);
    }
}