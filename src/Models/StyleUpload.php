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

namespace Btybug\Uploads\Models;

use File;
use Illuminate\Http\Request;
use Btybug\btybug\Helpers\helpers;
use Zipper;

/**
 * Class TplUpload
 * @package Btybug\Packeges\Models
 */
class StyleUpload
{
    /**
     *
     */
    const ZIP = ".zip";

    /**
     *
     */
    const MIN_SIZE = 3;
    /**
     * @var mixed
     */
    public $uf;
    /**
     * @var
     */
    public $fileNmae;
    /**
     * @var
     */
    public $helper;
    /**
     * @var
     */
    public $generatedName;

    /**
     * @var
     */
    protected $dir;

    /**
     * TplUpload constructor.
     */
    public function __construct()
    {
        $this->helpers = new helpers;
        $this->uf = config('paths.styles_upl');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function upload(Request $request)
    {

        if ($request->hasFile('file')) {

            $extension = $request->file('file')->getClientOriginalExtension(); // getting image extension
            $zip_file = $request->file('file')->getClientOriginalName();
            $this->fileNmae = str_replace(self::ZIP, "", $zip_file);
            $request->file('file')->move($this->uf, $zip_file); // uploading file to given path

            try {
                $this->extract();
                $this->deleteFolderZip($this->fileNmae);
            } catch (Exception $e) {
                return ['message' => $e->getMessage(), 'code' => $e->getCode(), 'error' => true];
            }

            return ['folder' => $this->generatedName, 'data' => $this->fileNmae, 'code' => 200, 'error' => false];
        }
    }

    /**
     *
     */
    public function extract()
    {
        $fileName = $this->fileNmae;
        $this->generatedName = $fileName . '_' . uniqid();
        File::makeDirectory($this->uf . $this->generatedName);
        Zipper::make($this->uf . "/" . $fileName . self::ZIP)->extractTo($this->uf . $this->generatedName . '/');
    }

    /**
     * @param $fileName
     */
    public function deleteFolderZip($fileName)
    {
        File::deleteDirectory($this->uf . $fileName);
        File::delete($this->uf . $fileName . self::ZIP);
    }

    /**
     * @param $folder
     * @param $name
     * @return array|string
     */
    public function validateAndreturnData($folder, $name)
    {
        if (File::exists($this->uf . $folder . '/' . 'config.json')) {
            $file = $this->uf . $folder . '/' . 'config.json';
            $response = $this->validate($file, $folder);
            return $response;
        } else {
            if (File::exists($this->uf . $folder . '/' . $name . '/' . 'config.json')) {
                $file = $this->uf . $folder . '/' . $name . '/' . 'config.json';
                $response = $this->validate($file, $folder);

                return $response;
            } else {
                return ['error' => 'true', 'message' => 'config.json file is not exists'];
            }
        }

        return $this->uf . $folder . '/' . 'config.json';
    }

    /**
     * @param $file
     * @param $key
     * @return array
     */
    private function validate($file, $key)
    {
        $conf = File::get($file);
        if ($conf) {

            $conf = json_decode($conf, true);
            if (!isset($conf['type']))
                return ['message' => 'Type is required', 'code' => '404', 'error' => true];

            if (!isset($conf['style']))
                return ['message' => 'Style is required', 'code' => '404', 'error' => true];

            if (!isset($conf['slug']))
                return ['message' => 'slug is required', 'code' => '404', 'error' => true];

            if (!isset($conf['css_data']))
                return ['message' => 'Css data is required', 'code' => '404', 'error' => true];

            $conf['created_at'] = time('now');
            $json = json_encode($conf, true);
            File::put($file, $json);
            return ['data' => $conf, 'code' => '200', 'error' => false];
        }

        return ['message' => 'Json file is empty !!!', 'code' => '404', 'error' => true];
    }

    public function isCompress($file)
    {
        $ext = $file->getClientOriginalExtension();
        if ($file->getSize() < self::MIN_SIZE) {
            return false;
        }
        if ($ext == 'zip') {
            return 'zip';
        }
        return 0;
    }
}
