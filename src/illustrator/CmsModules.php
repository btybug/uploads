<?php
/**
 * Copyright (c) 2017. All rights Reserved BtyBug TEAM
 */

namespace Btybug\Uploads\illustrator;

use Mockery\Exception;

/**
 * Created by PhpStorm.
 * User: menq
 * Date: 8/4/17
 * Time: 11:20 PM
 */
class CmsModules
{
    private $cmsPath;
    private $installedPath;

    public function __construct()
    {
        $this->cmsPath = base_path('vendor' . DS . 'sahak.avatar' . DS . 'cms');
        $this->installedPath = base_path('vendor' . DS . 'composer' . DS . 'installed.json');
    }

    public function modules()
    {
        return $this->sortModules();
    }

    private function sortModules()
    {
        $installed = $this->getInstalled();
        $modules = $this->packages();
        $result = [];
        foreach ($modules as $namespace => $version) {
            foreach ($installed as $key => $value) {
                if ($namespace == $value['name']) {
                    $result[] = $value;
                }
            }
        }
        return collect($result);
    }

    private function getInstalled()
    {
        $installed = json_decode(\File::get($this->installedPath), true);
        return $installed;
    }

    private function packages()
    {
        return $this->cmsComposer()['require'];
    }

    private function cmsComposer()
    {
        $path = $this->cmsPath . DS . 'composer.json';
        if (\File::exists($path)) {
            return json_decode(\File::get($path), true);
        }
        throw new Exception('file ' . $path . ' does not exist ');
    }

    public function find($index)
    {
        $modules = $this->sortModules();
        return $modules[$index];
    }
}