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

namespace Btybug\Uploads\Interfaces;

/**
 * Interface vInterfase
 * @package Btybug\Packeges\Interfases
 */
interface vInterfase
{

    /**
     * @param array $data
     * @return mixed
     */
    public function check(array $data);

    /**
     * @param $path
     * @return mixed
     */
    public function json($path);

    /**
     * @param $file
     * @return mixed
     */
    public function isCompress($file);
}
