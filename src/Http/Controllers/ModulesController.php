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

namespace Btybug\Uploads\Http\Controllers;

use Btybug\btybug\Services\RenderService;
use Btybug\Uploads\Repository\Plugins;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\Console\Tests\Input\StringInput;

class ModulesController extends Controller
{
    public function getChilds ()
    {
        return view('uploads::index');
    }

    public function getIndex(Request $request)
    {

        $selected = null;
        $packages = new Plugins();
        $packages->modules();
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
        return view('uploads::Modules.index', compact('plugins', 'selected', 'enabled'));
    }

    public function getExplore($repository, $package)
    {
        $plugins = new Plugins();
        $plugins->modules();
        $plugin = $plugins->find($repository . '/' . $package);
        $units = $plugin->units();
        return view('uploads::Explores.index', compact('plugin', 'units'));
    }
}