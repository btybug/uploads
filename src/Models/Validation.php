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
use Btybug\Uploads\Interfaces\vInterfase;
use Validator;


/**
 * Class Validation
 * @package Btybug\Packeges\Models
 */
class Validation implements vInterfase
{

    /**
     *
     */
    const MIN_SIZE = 3;
    /**
     * @var string
     */
    private $json = 'module.json';
    /**
     * @var array
     */
    private $fields = ["name", "type", "slug", "version", "description", "enabled", "author", "author", "author_site", "admin_link"];
    /**
     * @var array
     */
    private $requirements = ['name', "type", "slug", "enabled"];

    /**
     * Validation constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param $file
     * @return bool|int|string
     */
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

    /**
     * @return array
     */
    public function admin_link_rules()
    {
        return array(
            'type' => 'required',
            'title' => 'required',
            'link' => 'required',
        );
    }

    /**
     * @param $path
     * @return array
     */
    public function json($path)
    {
        $path = $path . '/module.json';
        $result = array();

        //if the plug-in structure correct
        if (!File::exists($path)) {
            $result[] = 'Uploaded File is not a plugin.';
            return $result;
        }

        $r = substr($path, 0, strpos($path, '/module.json'));
        $a = explode('/', $r);
        $folder = end($a);
        $conf = File::get($path);
        $json_data = json_decode($conf, true);

        $error = json_last_error_msg();
        if ($json_data == null) {
            $result[] = $error . ": in module.json file";
            return $result;
        }

        $resultIsValid = $this->check($json_data);
        if ($resultIsValid) {
            foreach ($resultIsValid as $key => $value) {
                $result[] = $value;
            }
            if ($json_data['name'] != $folder) {
                $result[] = 'The name value from module.json must be same as folder name.';
            }
            return $result;
        }

        if ($json_data['type'] != 'module' && $json_data['type'] != 'addon') {
            $result[] = "Type can be only module or addon in module.json: in your file TYPE = " . $json_data['type'];
        }

        if ($json_data['name'] != $json_data['namespace'] && $json_data['type'] != 'addon') {
            $result[] = 'The name value from module.json must be same as namespace.';
        }

        if ($json_data['name'] != $folder) {
            $result[] = 'The name value from module.json must be same as folder name.';
        }

        if (strtolower($json_data['name']) != $json_data['slug']) {
            $result[] = 'The slug value from module.json must be same as name just in lowercase.';
        }
        return $result;
    }

    /**
     * @param array $data
     * @return array|int
     */
    public function check(array $data)
    {
        $v = Validator::make($data, $this->rules());
        if ($v->fails()) {
            $messages = $v->errors()->all();
            return $messages;
        }

        if ($data['type'] == 'addon') {
            $v = Validator::make($data, $this->addon_rules(), $this->addon_rules_messages($data));
            if ($v->fails()) {
                $messages = $v->errors()->all();
                return $messages;
            }
        }

        return 0;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            'name' => 'required|between:2,20',
            'type' => 'required',
            'slug' => 'required',
            'enabled' => 'required',
            'namespace' => 'required'
        );
    }

    /**
     * @return array
     */
    public function addon_rules()
    {
        return array(
            'module' => 'required|exists:modules,slug',
        );
    }

    /**
     * @param $data
     * @return array
     */
    public function addon_rules_messages($data)
    {
        return array(
            'module.exists' => 'We have no module with ' . $data['module'] . ' name',
        );
    }

    /**
     * @param $path
     * @return array|int
     */
    public function check_addon_file($path)
    {
        $result = array();
        $scanned_directory = array_diff(scandir($path), array('..', '.', 'module.json'));
        if (empty($scanned_directory)) {
            $result[] = 'This add on is empty and not usable';
            return $result;
        }

        return 0;
    }

}
