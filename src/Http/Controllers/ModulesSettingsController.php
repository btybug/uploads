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

use App\Http\Controllers\Controller;
use App\Models\ExtraModules\Structures;
use File;
use Illuminate\Http\Request;
use Btybug\btybug\Helpers\helpers;
use Btybug\btybug\Models\ContentLayouts\ContentLayouts;
use Btybug\btybug\Models\Templates\Units;
use Btybug\Modules\Models\AdminPages;
use Btybug\Modules\Models\Routes;
use Btybug\User\Models\Roles;

/**
 * Class ModulesController
 * @package Btybug\Modules\Http\Controllers
 */
class ModulesSettingsController extends Controller
{

    public $modules;
    public $extra_modules;
    public $module_path;
    public $page_menu;
    public $types;

    public function __construct()
    {
        $this->modules = json_decode(\File::get(storage_path('app/modules.json')));
        $this->extra_modules = json_decode(\File::get(storage_path('app/plugins.json')));
        $this->module_path = "/admin/uploads/modules";
        $this->page_menu = "configMenu";
        $this->types = @json_decode(File::get(config('paths.unit_path') . 'configTypes.json'), 1)['types'];
    }

    public function getMain($basename)
    {
        return view("uploads::modules.settings.main", compact(['basename']));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex($slug)
    {
        $module = $this->validateModule($slug);

        if (!$module) return redirect($this->module_path);

        return view('uploads::modules.settings.general', compact(['slug', 'module', 'page_menu']));
    }

    private function validateModule($basename)
    {
        if (isset($this->modules->$basename)) {
            return $this->modules->$basename;
        } else {
            if (isset($this->extra_modules->$basename)) {
                return $this->extra_modules->$basename;
            }
        }

        return false;
    }

    public function getGears($slug)
    {
        $module = $this->validateModule($slug);

        if (!$module) return redirect($this->module_path);

        $types = $this->types;
        $type = 'frontend';
        $ui_units = Units::getAllUnits()->where('section', $type)->run();

        return view('uploads::modules.settings.gears', compact(['slug', 'ui_units', 'type', 'types']));
    }

    public function getAssets($slug)
    {
        return view('uploads::modules.settings.assets', compact(['slug']));
    }

    public function getBuild($slug)
    {
        return view('uploads::modules.settings.build', compact(['slug']));
    }

    public function getPermission($slug)
    {
        $module = $this->validateModule($slug);

        if (!$module) return redirect($this->module_path);

        $menu = BBgetAdminMenu($slug);
        $roles = Roles::pluck('id', 'name');

        if ($module->type == 'core') {
            $files = helpers::rglob('app/Modules/' . $slug . '/Resources/Views');
        } else {
            $files = helpers::rglob('app/ExtraModules/' . $slug . '/views');
        }
        $page_menu = "permission";

        return view('uploads::modules.settings.permission', compact(['slug', 'module', 'menu', 'roles', 'files', 'page_menu']));
    }

    public function getCode($slug)
    {
        $module = $this->validateModule($slug);

        if (!$module) return redirect($this->module_path);

        $file_indexes = [];

        if (isset($module->path) && File::isDirectory(base_path() . $module->path)) {
            $files = File::allFiles(base_path() . $module->path);
            foreach ($files as $file) {
                $f = [];
                $f['full_path'] = $file;
                $file = substr($file, strpos($file, "/" . $module->name) + 1);
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                $f['path'] = $file;
                $f['ext'] = $ext;
                if (!strpos($file, 'module.json')) {
                    $file_indexes[] = $f;
                }
            }
        }

        return view('uploads::modules.settings.code', compact(['slug', 'module', 'file_indexes']));
    }

    public function getTables($slug, $active = 0)
    {
        $module = $this->validateModule($slug);

        if (!$module) return redirect($this->module_path);

        $createForm = null;
        if (isset($module->tables) and isset($module->tables[$active]))
            $createForm = Forms::where('table_name', $module->tables[$active])->first();
        $page_menu = "configMenu";
        return view('uploads::modules.settings.tables', compact(['slug', 'module', 'active', 'createForm', 'page_menu']));
    }

    public function getViews($slug)
    {
        $module = $this->validateModule($slug);
        if (!$module) return redirect($this->module_path);

        $layouts = ContentLayouts::findByType('admin_template');

        return view('uploads::modules.settings.views', compact(['slug', 'module', 'layouts']));
    }

    public function getPages($slug, Request $request)
    {
        $pageID = $request->get('page');
        $module = $this->validateModule($slug);

        if (!$module) return redirect($this->module_path);

        $pageGrouped = AdminPages::groupBy('module_id')->get();
        $pages = AdminPages::pluck('title', 'id')->all();
        $modulesList = array($module);
        $type = 'pages';
        $layouts = ContentLayouts::pluck('slug', 'name');

        if ($pageID) {
            $page = AdminPages::find($pageID);
        } else {
            $page = AdminPages::where('module_id', $slug)->first();
        }

        if ($page && !$page->layout_id) $page->layout_id = 0;

        return view('uploads::modules.settings.build.pages', compact(['pageGrouped', 'pages', 'modulesList', 'layouts', 'module', 'slug', 'type', 'layouts', 'page']));
    }

    public function getMenus($slug, Request $request)
    {

        $type = "backend";
        $menu = $request->get('p');
        $module = $this->validateModule($slug);

        if (!$module) return redirect($this->module_path);

        $menus = [];

        if (isset($module->path) && File::isDirectory(base_path() . $module->path)) {
            $files = File::allFiles(base_path() . $module->path . "/Config/BackBuild/Menus");
            foreach ($files as $file) {
                if (!strpos($file, '.gitkeep')) {
                    $menus[] = $file;
                }
            }
        }

        if (count($menus)) if (!$menu) $menu = $menus[0]->getBasename('.json');

        $roles = Roles::where('slug', '!=', 'user')->get();
        return view('uploads::modules.settings.build.menus', compact(['slug', 'type', 'menus', 'roles', 'menu']));
    }

    public function postCreateMenus($slug, Request $request)
    {
        $module = $this->validateModule($slug);

        if (!$module) return redirect($this->module_path);

        $name = $request->get('name');

        File::put(base_path() . $module->path . "/Config/BackBuild/Menus/" . $name . ".json", json_encode([], true));

        return redirect()->back();
    }

    public function getMenuEdit($menu, $slug)
    {
        $pageGrouped = AdminPages::groupBy('module_id')->get();
        $html = Routes::getModuleRoutes('GET', 'admin/uploads/modules')->html();
        return view('uploads::modules.settings.build.edit_menus', compact(['slug', 'pageGrouped', 'menu', 'html']));
    }

    public function getUrls($slug)
    {
        $html = Routes::getModuleRoutes('GET', 'admin/sahak')->html();
        return view('uploads::modules.settings.build.urls', compact(['slug', 'html']));
    }

    public function getClassify($slug)
    {
        return view('uploads::modules.settings.build.classify', compact(['slug']));
    }
}
