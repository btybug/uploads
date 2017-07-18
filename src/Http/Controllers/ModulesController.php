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

namespace Sahakavatar\Uploads\Http\Controllers;

use App\Core\FormRegister;
use App\helpers\helpers;
use App\Http\Controllers\Controller;
use App\Models\ExtraModules\Structures;
use Sahakavatar\Console\Models\Menu;
use App\Models\AdminPages;
use Sahakavatar\Modules\Models\Routes;
use Sahakavatar\Uploads\Models\Upload;
use Sahakavatar\Uploads\Models\Validation as validateUpl;
use File;
use Illuminate\Http\Request;
use App\Core\CmsItemRegister;

/**
 * Class ModulesController
 * @package Sahakavatar\Modules\Http\Controllers
 */
class ModulesController extends Controller
{
    /**
     * @var Upload
     */
    public $upload;
    /**
     * @var validateUpl
     */
    public $validateUpl;
    /**
     * @var helpers
     */
    public $helper;
    /**
     * @var Module
     */
    protected $modules;
    /**
     * @var mixed
     */
    public $up;
    /**
     * @var mixed
     */
    public $mp;
    /**
     * @var
     */
    public $upplugin;

    /**
     * ModulesController constructor.
     * @param Module $module
     * @param Upload $upload
     * @param validateUpl $v
     */
    public function __construct(Upload $upload, validateUpl $v)
    {
        $this->upload = $upload;
        $this->validateUpl = $v;
        $this->helper = new helpers();
        $this->up = config('paths.modules_upl');
        $this->mp = config('paths.extra_modules');
    }

    public function getIndexUploads()
    {
        return view('uploads::index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(Request $request)
    {
//        $path = base_path('app/ExtraModules/Test');
//        FormRegister::run($path);
//        dd("done!!!");
        $selected_module = $request->get('m');
        $extras = Structures::getExtraModules();
        $module = null;
        $addons = [];

        if (count($extras)) {
            $module = ($selected_module) ? (isset($extras[$selected_module])) ? $extras[$selected_module] : null : array_first($extras);
        }

        if ($module) $addons = BBGetModuleAddons($module->slug);


        return view('uploads::modules.list', compact(['module', 'addons', 'extras']));
    }

    /**
     *
     */
    public function errorShutdownHandler()
    {
        $last_error = error_get_last();
        if ($last_error['type'] === E_ERROR) {
            // fatal error
            $this->customError(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
        }

        if ($last_error['type'] === E_NOTICE) {
            return $this->customError(E_NOTICE, $last_error['message'], $last_error['file'], $last_error['line']);
        }
    }

    /**
     * @param $errno
     * @param $errstr
     * @param $file
     * @param $line
     */
    public function customError($errno, $errstr, $file, $line)
    {
        File::deleteDirectory($this->upplugin);

        \Session::set('error', "<b>Error:</b> [$errno] $errstr<br> on line $line in $file file ");
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse|string
     */
    public function postUpload(Request $request)
    {

        $isValid = $this->validateUpl->isCompress($request->file('file'));

        if (!$isValid) return $this->upload->ResponseError('Uploaded data is InValid!!!', 500);

        $response = $this->upload->upload($request);

        if (!$response['error']) {
            $result = $this->upload->validatConfAndMoveToMain($response['folder'], $response['data']);
            File::deleteDirectory($this->up, true);
            if ($result['error']) return $result;

            switch ($result['data']['type']) {
                case 'plugin':
                    $structure = Structures::getStructure($result['data']['module']);
                    if ($structure) {
                        if ($structure->type == 'core') {
                            $this->upplugin = base_path('app/Modules/' . ucfirst($result['data']['module']) . '/Plugins/' . $result['data']['namespace']);
                            $path = base_path('app/Modules/' . ucfirst($result['data']['module']) . '/Plugins/' . $result['data']['namespace']);
                            $registerPath = 'app/Modules/' . ucfirst($result['data']['module']) . '/Plugins/' . $result['data']['namespace'];
                        } else {
                            $this->upplugin = base_path('app/ExtraModules/' . ucfirst($result['data']['module']) . '/Plugins/' . $result['data']['namespace']);
                            $path = base_path('app/ExtraModules/' . ucfirst($result['data']['module']) . '/Plugins/' . $result['data']['namespace']);
                            $registerPath = 'app/ExtraModules/' . ucfirst($result['data']['module']) . '/Plugins/' . $result['data']['namespace'];
                        }
                    }
                    break;
                case 'addon':

                    break;
                case 'extra':
                    $this->upplugin = base_path('app/ExtraModules/' . $result['data']['namespace']);
                    $path = base_path('app/ExtraModules/' . $result['data']['namespace']);
                    $registerPath = 'app/ExtraModules/' . $result['data']['namespace'];
                    break;
            }

//            $form_result = $this->upload->validateForms($this->upplugin);

//            if ($form_result['error']) {
//                File::deleteDirectory($path);
//                return $form_result;
//            }
            if (isset($result['data']['autoload'])) {
                switch ($result['data']['type']) {
                    case 'plugin':
                        $structure = Structures::getStructure($result['data']['module']);
                        if ($structure) {
                            if ($structure->type == 'core') {
                                $autoloadClass = 'Sahakavatar\\' . ucfirst($result['data']['module']) . '\Plugins\\' . $result['data']['namespace'] . '\\' . $result['data']['autoload'];
                            } else {
                                $autoloadClass = 'App\ExtraModules\\' . ucfirst($result['data']['module']) . '\Plugins\\' . $result['data']['namespace'] . '\\' . $result['data']['autoload'];
                            }
                        }
                        break;
                    case 'addon':
                        break;
                    case 'extra':
                        $autoloadClass = 'App\ExtraModules\\' . $result['data']['namespace'] . '\\' . $result['data']['autoload'];
                        break;
                }


                if (!class_exists($autoloadClass)) {
                    File::deleteDirectory($path);

                    return ['message' => 'Autoload Class does not exists', 'code' => '500', 'error' => true];
                }

                $autoload = new $autoloadClass();
                try {
                    $autoload->up($result['data']);
                } catch (\Exception $e) {
                    File::deleteDirectory($path);

                    return ['message' => $e->getMessage(), 'code' => $e->getCode(), 'error' => true];
                }

            };

            if (File::exists($path . '/' . $result['data']['route'])) {
                $response = $this->checkSyntax($this->upplugin . '/' . $result['data']['route']);
                if ($response) {
                    if (isset($result['data']['autoload'])) {
                        $autoload->down($result['data']);
                    }

                    File::deleteDirectory($this->upplugin);

                    return $response;
                }

                set_error_handler([$this, 'customError']);
                register_shutdown_function([$this, 'errorShutdownHandler']);

                try {
                    include $path . '/' . $result['data']['route'];
                } catch (\Exception $e) {
                    if ($e->getCode() != '-1') {
                        if (isset($result['data']['autoload'])) {
                            $autoload->down($result['data']);
                        }
                        File::deleteDirectory($path);

                        return ['message' => $e->getMessage(), 'code' => $e->getCode(), 'error' => true];
                    }
                }

                if (\Session::has('error')) {
                    $error = \Session::pull('error');
                    if (isset($result['data']['autoload'])) {
                        $autoload->down($result['data']);
                    }

                    return ['message' => $error, 'code' => 500, 'error' => true];
                }

            }

//            PluginForms::registration($this->upplugin,$result['data']['slug']);
            Structures::create($result['data']);
            \Artisan::call('plugin:optimaze');
            CmsItemRegister::run($path, $registerPath, $result['data']);
            FormRegister::run($path);

            //TODO: make pages registration
            Routes::registrePages($result['data']['slug']);
//
//            $pages = AdminPages::where('module_id',$result['data']['slug'])->where('parent_id',0)->get();
//            Menu::registerFromAdminPages($pages);
            if ($result['data']['type'] == 'extra') {
//                $this->MakeConfigPages($result['data']['namespace'], $result['data']['slug']);
            }

            return $result;
        } else {
            File::deleteDirectory($this->up, true);
            return $response;
        }
    }

    private function MakeConfigPages($moduleName, $slug, $id = 0)
    {
        $menu = base_path('app/Modules/Console/menuPages.json');
        $menu = json_decode(\File::get($menu), true);
        $parentPageSlug = 'console_modules';
        $parentPage = AdminPages::where('slug', $parentPageSlug)->first();
        $row = BBRegisterAdminPages("Console", $moduleName, "/admin/console/modules/" . $slug, null, $parentPage->id);
        foreach ($menu['items'] as $item) {
            $url = str_replace('here', $slug, $item['url']);
            $parent = BBRegisterAdminPages("Console", $moduleName . " " . $item['title'], $url, null, $row->id);
            if (isset($item['childs']) && count($item['childs'])) {
                foreach ($item['childs'] as $ch) {
                    BBRegisterAdminPages("Console", $moduleName . " " . $item['title'] . "-" . $ch, $url . "/" . $ch, null, $parent->id);
                }
            }
        }
    }

    /**
     * @param $fileName
     * @return array|bool
     */
    public function checkSyntax($fileName)
    {
        // Sort out the formatting of the filename
        $fileName = realpath($fileName);

        // Get the shell output from the syntax check command
        $output = shell_exec('php -l "' . $fileName . '"');

        // Try to find the parse error text and chop it off
        $syntaxError = preg_replace("/Errors parsing.*$/", "", $output, -1, $count);

        // If the error text above was matched, return the message containing the syntax error
        if ($count > 0) {
            return ['message' => trim($syntaxError), 'code' => 500, 'error' => true];
        }

        return false;
    }

    public function postCreateMenu($module, Request $request)
    {

    }

    public function postMenuEdit($module, $menu, $role, Request $request)
    {
        dd($module, $menu, $role, $request->all());
    }

    public function getTest()
    {
//        $menu=new Menu;
//        dd($menu);
        dd(Menu::findByPlugin('58865e40b3f5e'));

    }

}
