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

use App\Models\ExtraModules\Modules;
use App\Models\ExtraModules\Structures;
use App\Models\MenuData;
use File;
use Illuminate\Http\Request;
use Btybug\btybug\Helpers\helpers;
use Zipper;


/**
 * Class Upload
 * @package Btybug\Modules\Models
 */
class Upload
{

    /**
     *
     */
    const ZIP = ".zip";
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
     * @var mixed
     */
    public $coreModule;

    /**
     * Upload constructor.
     */
    public function __construct()
    {
        $modules = json_decode(File::get(storage_path('app/modules.json')));
        $this->helpers = new helpers;
        $this->uf = config('paths.modules_upl');
        $this->coreModule = $modules;
    }

    /**
     * @param $data
     * @param $code
     * @param null $links
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function ResponseSuccess($data, $code, $links = null, $id = null)
    {
        return \Response::json(['data' => $data, 'invalid' => false, 'id' => $id, 'links' => $links, 'code' => $code, 'error' => false], $code);
    }

    /**
     * @param $data
     * @param $code
     * @param $messages
     * @return \Illuminate\Http\JsonResponse
     */
    public function ResponseInvalid($data, $code, $messages)
    {
        return \Response::json(['data' => $data, 'invalid' => true, 'messages' => $messages, 'code' => $code, 'error' => false], $code);
    }

    /**
     * @param $message
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function ResponseError($message, $code)
    {
        return \Response::json(['message' => $message, 'code' => $code, 'error' => true], $code);
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
        $this->generatedName = uniqid();
        File::makeDirectory($this->uf . $this->generatedName);
        Zipper::make($this->uf . "/" . $fileName . self::ZIP)->extractTo($this->uf . $this->generatedName . '/');
    }

    /**
     * @param $folder
     * @param $name
     * @return array|string
     */
    public function validatConfAndMoveToMain($folder, $name)
    {
        if (File::exists($this->uf . $folder . '/' . 'module.json')) {
            $file = $this->uf . $folder . '/' . 'module.json';
            $response = $this->validateModule($file, $folder);
            if ($response['error'])
                return $response;
            $path = '';
            switch ($response['data']['type']) {
                case 'plugin':
                    $structure = Structures::getStructure($response['data']['module']);
                    if ($structure) {
                        if ($structure->type == 'core') {
                            $dir = config('paths.modules_path') . ucfirst($response['data']['module']) . '/Plugins';
                            $path = "/app/Modules/" . ucfirst($response['data']['module']) . '/Plugins/' . $response['data']['namespace'];
                            $dir_transfer = config('paths.modules_path') . ucfirst($response['data']['module']) . '/Plugins/' . $response['data']['namespace'];
                        } else {
                            $dir = config('paths.extra_modules') . '/' . ucfirst($response['data']['module']) . '/Plugins';
                            $path = "/app/ExtraModules/" . ucfirst($response['data']['module']) . '/Plugins/' . $response['data']['namespace'];
                            $dir_transfer = config('paths.extra_modules') . '/' . ucfirst($response['data']['module']) . '/Plugins/' . $response['data']['namespace'];
                        }

                        if (!File::isDirectory($dir)) {
                            File::makeDirectory($dir);
                        }

                        File::copyDirectory($this->uf . $folder, $dir_transfer);
                    }

                    break;
                case 'addon':

                    break;
                case 'extra':
                    $path = "/app/ExtraModules/" . $response['data']['namespace'];
                    File::copyDirectory($this->uf . $folder, config('paths.extra_modules') . '/' . $response['data']['namespace']);
                    break;
            }

            $response['data']['path'] = $path;
            return $response;
        } else {
            if (File::exists($this->uf . $folder . '/' . $name . '/' . 'module.json')) {
                $file = $this->uf . $folder . '/' . $name . '/' . 'module.json';
                $response = $this->validateModule($file, $folder);

                if ($response['error'])
                    return $response;

                switch ($response['data']['type']) {
                    case 'plugin':
                        $structure = Structures::getStructure($response['data']['module']);

                        if ($structure) {
                            if ($structure->type == 'core') {
                                $dir = config('paths.modules_path') . ucfirst($response['data']['module']) . '/Plugins';
                                $path = "/app/Modules/" . ucfirst($response['data']['module']) . '/Plugins/' . $response['data']['namespace'];
                                $dir_transfer = config('paths.modules_path') . ucfirst($response['data']['module']) . '/Plugins/' . $response['data']['namespace'];
                            } else {
                                $dir = config('paths.extra_modules') . '/' . ucfirst($response['data']['module']) . '/Plugins';
                                $path = "/app/ExtraModules/" . ucfirst($response['data']['module']) . '/Plugins/' . $response['data']['namespace'];
                                $dir_transfer = config('paths.extra_modules') . '/' . ucfirst($response['data']['module']) . '/Plugins/' . $response['data']['namespace'];
                            }

                            if (!File::isDirectory($dir)) {
                                File::makeDirectory($dir);
                            }

                            File::copyDirectory($this->uf . $folder . '/' . $name, $dir_transfer);
                        }
                        break;
                    case 'addon':

                        break;
                    case 'extra':
                        $path = "/app/ExtraModules/" . $response['data']['namespace'];
                        File::copyDirectory($this->uf . $folder . '/' . $name, config('paths.extra_modules') . '/' . $response['data']['namespace']);
                        break;
                }

                $response['data']['path'] = $path;
                return $response;
            }
        }

        return $this->uf . $folder . '/' . 'module.json';
    }

    /**
     * @param $file
     * @param $key
     * @return array
     */
    private function validateModule($file, $key)
    {
        $conf = File::get($file);
        if ($conf) {
            $conf = json_decode($conf, true);
            if (!isset($conf['namespace']) && !isset($conf['type']))
                return ['message' => 'Namespace and type are required', 'code' => '404', 'error' => true];
            if ($conf['type'] == 'plugin') {
                if (!isset($conf['module'])) return ['message' => 'Module key is required', 'code' => '404', 'error' => true];

                if (!Structures::find($conf['module']) &&
                    !isset($this->coreModule->{$conf['module']}))
                    return ['message' => $conf['module'] . ' Module is not exists, check your json file', 'code' => '404', 'error' => true];

                if ($m = Structures::find($conf['module'])) {
                    $conf['module'] = $m->slug;
                } else {
                    $m = $this->coreModule->{$conf['module']};
                    $conf['module'] = $m->slug;
                }
            }

            $modules = json_decode(File::get(storage_path('app/modules.json')));
            $extras = Structures::getExtraModules();

            if (isset($modules->{$conf['namespace']}) or isset($extras[$conf['namespace']])) {
                return ['message' => "The Module by this " . $conf['namespace'] . " namespace exists !!!", 'code' => '404', 'error' => true];
            }

            $conf['slug'] = $conf['namespace'];
            $conf['basename'] = $conf['namespace'];
            $conf['created_at'] = time('now');
            $json = json_encode($conf, true);
            File::put($file, $json);
            return ['data' => $conf, 'code' => '200', 'error' => false];
        }

        return ['message' => 'Json file is empty !!!', 'code' => '404', 'error' => true];
    }

    /**
     * @param $path
     * @return array
     */
    public function validateForms($path)
    {
        $forms = File::files($path . '\forms');
        $form_error = ['error' => false];
        if (count($forms) > 0) {
            foreach ($forms as $form) {
                $form_result = PluginForms::checkDB($form);
                if ($form_result['error']) {
                    $form_error = $form_result;
                    break;
                }
            }
        }

        return $form_error;
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
     * @param $id
     */
    public function removeLinks($id)
    {
        $menu = MenuData::where('plugin_id', $id)->first();
        if ($menu) {
            $menu->delete();
        }
    }

    /**
     * @param $admin_links
     * @param $plugin_id
     * @param $module_id
     */
    public function removeAddonLinks($admin_links, $plugin_id, $module_id)
    {
        $menu = MenuData::where('id', $module_id)->first();
        if ($menu->sub_items) {
            $links = json_decode($menu->sub_items, true);
            foreach ($links as $k => $v) {
                if (isset($v['data_id'])) {
                    if ($v['data_id'] == $plugin_id) {
                        unset($links[$k]);
                    }
                }
            }

            $menu->sub_items = json_encode($links, true);
            $menu->save();
        }
    }

    /**
     * @param $conf
     * @return array
     */
    public function returnLinks($conf)
    {
        if (isset($conf['admin_link']['children'])) {
            $children = [];
            $links = [];
            $children = $conf['admin_link']['children'];
            foreach ($children as $child) {
                $links[] = $child['link'];
            }

            return $links;
        }
    }

    /**
     * @param $conf
     * @return array
     */
    public function returnAddonLinks($conf)
    {
        if (isset($conf['admin_link'])) {
            $children = [];
            $links = [];
            $children = $conf['admin_link'];
            foreach ($children as $child) {
                $links[] = $child['link'];
            }

            return $links;
        }
    }
}
