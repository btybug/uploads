<?php
/**
 * Copyright (c) 2017. All rights Reserved BtyBug TEAM
 */

/**
 * Created by PhpStorm.
 * User: menq
 * Date: 8/15/17
 * Time: 2:43 PM
 */

namespace Btybug\Uploads\Http\Controllers;


use Btybug\Uploads\Repository\Plugins;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\Console\Tests\Input\StringInput;

class AppsController extends Controller
{
    public function getIndex(Request $request)
    {

        $selected = null;
        $packages = new Plugins();
        $packages->appPlugins();
        $plugins = $packages->getPlugins();
        if ($request->p && isset($plugins[$request->p])) {
            $selected = $packages->find($plugins[$request->p]['name']);
        } elseif ($request->p && !isset($plugins[$request->p])) {
            abort('404');
        } elseif (!$request->p && !isset($plugins[$request->p])) {
            foreach ($plugins as $key => $plugin) {
                $selected = $packages->find($key);
                continue;
            }
        }
        $storage = $packages->getStorage();
        $enabled = true;
        if (isset($selected->name) && isset($storage[$selected->name])) {
            $enabled = false;
        }
        return view('uploads::Apps.index', compact('plugins', 'selected', 'enabled'));
    }
    public function getExtra(Request $request)
    {

        $selected = null;
        $packages = new Plugins();
        $packages->appPlugins();
        $plugins = $packages->getPlugins();
        if ($request->p && isset($plugins[$request->p])) {
            $selected = $packages->find($plugins[$request->p]['name']);
        } elseif ($request->p && !isset($plugins[$request->p])) {
            abort('404');
        } elseif (!$request->p && !isset($plugins[$request->p])) {
            foreach ($plugins as $key => $plugin) {
                $selected = $packages->find($key);
                continue;
            }
        }
        $storage = $packages->getStorage();
        $enabled = true;
        if (isset($selected->name) && isset($storage[$selected->name])) {
            $enabled = false;
        }
        return view('uploads::Apps.index', compact('plugins', 'selected', 'enabled'));
    }

    public function getExplore($repository, $package)
    {
        $plugins = new Plugins();
        $plugins->plugins();
        $plugin = $plugins->find($repository . '/' . $package);
        $units = $plugin->units();
        return view('uploads::Explores.index', compact('plugin', 'units'));
    }
}