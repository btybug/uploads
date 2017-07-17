<?php
/**
 * Copyright (c) 2016.
 * *
 *  * Created by PhpStorm.
 *  * User: Edo
 *  * Date: 10/3/2016
 *  * Time: 10:44 PM
 *
 */

namespace App\Modules\Uploads\Http\Controllers;

use App\Core\CmsItemReader;
use App\Core\CmsItemUploader;
use App\Http\Controllers\Controller;
use App\Models\ExtraModules\Modules;
use App\Models\ExtraModules\Structures;
use App\Models\Setting;
use App\Models\Taxonomy;
use App\Models\Templates\Templates;
use App\Models\Templates\TplVariations;
use App\Models\Templates\UiElements;
use App\Models\Themes\Themes;
use App\Modules\Create\Models\Corepage;
use Illuminate\Http\Request;
use App\Modules\Settings\Models\Template;
use App\helpers\helpers;
use App\helpers\dbhelper;
use App\Modules\Settings\Models\TemplateVariations;
use App\Models\Templates\Templates as Tpl;
use App\Modules\Settings\Models\TplUpload;
use App\Modules\Resources\Models\Validation as validateUpl;
use Input, Session, File, Zipper, View, Auth,
    Validator,
    Datatables,
    Resources;

/**
 * Class TemplateController
 *
 * @package App\Modules\Packeges\Http\Controllers
 */
class HfController extends Controller
{

    /**
     * @var helpers|null
     */
    private $helpers = null;
    /**
     * @var null|string
     */
    private $rootpath = null;
    /**
     * @var null|string
     */
    private $index_path = null;
    /**
     * @var Templates|null
     */
    private $templates = null;
    /**
     * @var packegehelper|null
     */
    private $phelper = null;
    /**
     * @var mixed|string
     */
    private $tmp_upload = '';
    /**
     * @var dbhelper|string
     */
    private $dhelper = "";
    /**
     * @var
     */
    private $upload;
    /**
     * @var
     */
    private $validateUpl;
    /**
     * @var mixed
     */
    private $up;
    /**
     * @var mixed
     */
    private $tp;

    private $types;

    /**
     * TemplateController constructor.
     * @param Templates $templates
     * @param TplUpload $tplUpload
     * @param validateUpl $validateUpl
     */
    public function __construct()
    {
        $this->helpers = new helpers;
        $this->rootpath = templatesPath();
        $this->index_path = "/admin/templates/";
        $this->tmp_upload = config('paths.tmp_upload');
        $this->dhelper = new dbhelper;
        $this->upload = new CmsItemUploader('HF');
        $this->up = config('paths.tmp_upl_packages');
        $this->tp = config('paths.template_path');
        $this->types = @json_decode(File::get(config('paths.template_path') . 'configTypes.json'), 1)['types'];
    }

    /**
     * @return View
     */
    public function getIndex (Request $request)
    {

        $type=$request->get('type','header');
        $slug = $request->get('p');
        $currentTemplate = null;
        $templates = CmsItemReader::getAllGearsByType('HF')
            ->where('place', 'frontend')
            ->where('type', $type)
            ->run();

        if (count($templates) && $slug) {
            $currentTemplate = CmsItemReader::getAllGearsByType('HF')
                ->where('place', 'frontend')
                ->where('type', $type)
                ->where('slug', $slug)
                ->first();
        } elseif (count($templates)) {
            $currentTemplate = CmsItemReader::getAllGearsByType('HF')
                ->where('place', 'frontend')
                ->where('type', $type)
                ->first();
        }

        $variations = $currentTemplate ? $currentTemplate->variations() : [];
        return view('uploads::gears.hf.index', compact(['templates','type','variations','currentTemplate']));
    }



    public function postNewType(Request $request)
    {
        $title = $request->get('title');
        $foldername = str_replace(' ', '', strtolower($title));
        $type = "body";
        $general = array_where($this->types, function ($value, $key) use ($type) {
            return ($value['foldername'] == $type);
        });
        $key = key($general);
        if (isset($general[$key]['subs'])) {
            $result = array_search($foldername, array_column($general[$key]['subs'], 'foldername'));
            if ($result === false) {
                $this->types[$key]['subs'][] = [
                    'title' => $title,
                    'foldername' => $foldername,
                    'type' => 'custom',
                ];
            } else {
                return redirect()->back()->with('message', 'Please enter new Type Title, "' . $title . '" type aleardy exist type!!!');
            }
        } else {
            $this->types[$key]['subs'][] = [
                'title' => $title,
                'foldername' => $foldername,
                'type' => 'custom',
            ];
        }

        $this->types[$key]['subs'] = array_values($this->types[$key]['subs']);
        File::put(config('paths.template_path') . 'configTypes.json', json_encode(['types' => $this->types], 1));
        File::makeDirectory($this->tp . $type . '/' . $foldername);
        File::put($this->tp . $type . '/' . $foldername . '/.gitignor', '');

        return redirect()->back()->with('message', 'New Type successfully created');

    }

    public function postDeleteType(Request $request)
    {
        $foldername = $request->get('folder');
        $type = "body";
        $general = array_where($this->types, function ($value, $key) use ($type) {
            return ($value['foldername'] == $type);
        });
        $key = key($general);

        if (isset($general[$key]['subs'])) {
            $result = array_search($foldername, array_column($general[$key]['subs'], 'foldername'));
            if ($result !== false) {
                $types = $general[$key]['subs'];
                unset($types[$result]);
                $general[$key]['subs'] = array_values($types);
                $this->types[$key] = $general[$key];
                File::put(config('paths.template_path') . 'configTypes.json', json_encode(['types' => $this->types], 1));
                if (File::isDirectory($this->tp . $type . '/' . $foldername)) {
                    File::deleteDirectory($this->tp . $type . '/' . $foldername);
                }

                return \Response::json(['error' => false]);
            }
        }


        return \Response::json(['message' => 'Please try again', 'error' => true]);
    }

    /**
     *
     */

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postTemplatesWithType(Request $request)
    {
        $main_type = $request->get('main_type');
        $general_type = $request->get('type', null);

        if ($general_type) {
            $templates = Tpl::where('general_type', $general_type)->where('type', $main_type)->run();
        } else {
            $templates = Tpl::where('general_type', $main_type)->run();
        }

        if ($general_type or $main_type == 'body') {
            $html = View::make('console::backend.gears.templates._partrials.tpl_list_cube', compact(['templates']))->render();
        } else {
            $html = View::make('console::backend.gears.templates._partrials.tpl_list', compact(['templates']))->render();
        }


        return \Response::json(['html' => $html, 'error' => false]);
    }

    public function postTemplatesInModal(Request $request)
    {
        $main_type = $request->get('main_type');
        $general_type = $request->get('type', null);

        if ($general_type) {
            $templates = Tpl::where('general_type', $general_type)->where('type', $main_type)->run();
        } else {
            $templates = Tpl::where('general_type', $main_type)->run();
        }
        $html = View::make('console::backend.gears.templates._partrials.tpl_modal_list', compact(['templates']))->render();


        return \Response::json(['html' => $html, 'error' => false]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function postUpload(Request $request)
    {
        return $this->upload->run($request,'frontend');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postDelete(Request $request)
    {
        $slug = $request->get('slug');
        $hf = CmsItemReader::getAllGearsByType('HF')
            ->where('place', 'frontend')
            ->where('slug', $slug)
            ->first();
        if($hf) {
            $deleted = $hf->deleteGear();
            return \Response::json(['success' => $deleted, 'url' => url('/admin/uploads/gears/h-f')]);
        }
    }

    /**
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse|View
     */
    public function getTplVariations($slug)
    {
        $template = Tpl::find($slug);
        if (!count($template)) return redirect()->back();
        $variation = [];
        $variations = $template->variations();

        //conditon if type is section
        if ($template->type == 'single_section' || $template->type == 'all_section') {
            $sections = Sections::lists('blog_slug', 'id')->all();
        }

        if ($template->type == 'taxonomies' || $template->type == 'terms') {
            $taxonomies = Taxonomy::all();
        }


        return view(
            'console::backend.gears.templates.variations',
            compact(['template', 'variations', 'title', 'sections', 'variation', 'taxonomies'])
        );
    }

    /**
     * @param Request $request
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postTplVariations(Request $request, $slug)
    {
        $template = Tpl::find($slug);
        if (!$template) return redirect()->back();
        $template->makeVariation($request->except('_token', 'template_slug'))->save();

        return redirect()->back();
    }


    public function TemplatePreview($id)
    {
        $slug = explode('.', $id);
        $ui = Tpl::find($slug[0]);
        if(!$ui) {
            abort('404');
        }
        $variation = Tpl::findVariation($id);
//        if (!$variation) return redirect()->back();
        $ifrem = array();
        $settings = (isset($variation->settings) && $variation->settings) ? $variation->settings : [];
        $ifrem['body'] = url('/admin/uploads/gears/h-f/settings-edit-theme',$id);
        return view('uploads::gears.hf.preview', compact(['ui', 'id', 'ifrem', 'settings', 'variation']));
    }



    public function TemplatePreviewIframe($id, $page_id = null, $edit = false)
    {

        $page = Corepage::find($page_id);
        $theme=Themes::active();
        if ($page) {
            $page_data = @json_decode($page->data_option,true);
            $htmlBody = $theme->renderLayout($id,['settings'=>$page_data]);
        }
        return view('uploads::.gears.hf._partials.ifpreview', compact(['htmlBody','id', 'edit']));
    }

    public function TemplatePreviewEditIframe($id)
    {
        $slug = explode('.', $id);
        $ui = Tpl::find($slug[0]);
        $variation = Tpl::findVariation($id);
//        if (!$variation) return redirect()->back();
        $settings = (isset($variation->settings) && $variation->settings) ? $variation->settings : [];
        $settings_json = json_encode($settings, true);
        $htmlSettings = "No Settings!!!";
        if (isset($ui->have_setting) && $ui->have_setting) {
            $htmlSettings = $ui->renderSettings(compact(['settings']));
        }
        $htmlBody = $ui->render(['settings' => $settings]);
        $settings_json = json_encode($settings, true);
        return view('uploads::gears.hf._partials.if_edit_preview', compact(['htmlBody', 'htmlSettings', 'settings_json', 'id', 'settings']));
    }

    public function postSettings(Request $request)
    {
        $output = Templates::saveSettings($request->id, $request->itemname, $request->except(['_token', 'itemname']), $request->save);
        $result =  $output ? ['html' => $output['html'], 'url' => url('/admin/uploads/gears/h-f/settings/' . $output['slug']), 'error' => false] : ['error' => true];
        return \Response::json($result);
    }


    /**
     * @param Request $request
     * @return null
     */
    public function getWidgetsVariations(Request $request)
    {
        $req = $request->all();
        if (isset($req['widget_id'])) {
            return BBGetWidgetsVariations($req['widget_id']);
        }

        return null;
    }



    // Variations
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postGetVariations(Request $request)
    {
        $id = $request->get('id');
        $variation = Tpl::findVariation($id);
        $slug = explode('.', $id);
        $html = View::make('uploads::gears.hf._partrials.edit_variation', compact(['variation', 'slug']))->render();

        return \Response::json(['html' => $html]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEditVariation(Request $request)
    {
        $variation = Tpl::findVariation($request->get('id'));
        $variation->title = $request->get('title');
        $variation->save();
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteVariation(Request $request) {
        $result = false;
        if($request->slug) {
            $result = Tpl::deleteVariation($request->slug);
        }
        return \Response::json(['success' => $result]);
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postSetting(Request $request)
    {
        $this->helpers->clearTplCache();
        $all_req = $request->all();
        $id = $all_req['id'];
        $template = Template::find($id);

        $template_img_path = $this->rootpath . '/' . $template->folder_name . "/images/";

        $data = $request->except(['_token', 'id', 'image']);

        if ($template->have_setting == 1) {
            //Preserve old images
            $image_field_name = @$all_req['image'];
            $settings_contents = unserialize($template->setting_contents);
            foreach ($image_field_name as $key => $val) {
                $data[$key] = (is_array($settings_contents) && array_key_exists(
                        $key,
                        $settings_contents
                    )) ? $settings_contents[$key] : "";
            }
            //image Manupulation
            if ($request->hasFile('image')) {
                $files = Input::file('image');
                foreach ($files as $key => $file) {
                    $imageName = $key . '.' . $file->getClientOriginalExtension();
                    $file->move($template_img_path, $imageName);
                    $data[$key] = $template_img_path . $imageName;
                }
            }
            $template->setting_contents = serialize($data);
            $template->save();
        }

        $data = $all_req['content'];
        $this->phelper->putFileData($this->rootpath . '/' . $template->folder_name . "/tpl.blade.php", $data);

        return redirect($this->index_path);
    }

    /**
     * @param Request $request
     */
//    public function postUpload(Request $request)
//    {
//        ini_set('upload_max_filesize', '100M');
//        ini_set('post_max_size', '100M');
//        ini_set('max_input_time', 30000);
//        ini_set('max_execution_time', 30000);
//
//        if ($request->hasFile('file')) {
//
//            $destinationPath = $this->rootpath; // upload path
//            $extension = Input::file('file')->getClientOriginalExtension(); // getting image extension
//            if ($extension != 'zip') {
//                Session::flash('message', 'Invalid File Type!');
//                Session::flash('alert-class', 'alert-danger');
//            } else {
//                $name = Input::file('file')->getClientOriginalName();
//                $tpl_name = str_replace(".zip", "", $name);
//                File::deleteDirectory($this->rootpath . "/" . $tpl_name);
//                Input::file('file')->move($destinationPath, $name); // uploading file to given path
//                File::makeDirectory($destinationPath . "/" . $tpl_name, 0755, true);
//                Zipper::make($destinationPath . "/" . $name)->extractTo($destinationPath . "/" . $tpl_name);
//
//                if (!File::exists($destinationPath . "/" . $tpl_name . "/conf.json")) {
//                    File::copyDirectory(
//                        $destinationPath . "/" . $tpl_name . "/" . $tpl_name,
//                        $destinationPath . "/" . $tpl_name
//                    );
//                    File::deleteDirectory($destinationPath . "/" . $tpl_name . "/" . $tpl_name);
//                }
//                $conf_arr = file_get_contents($destinationPath . "/" . $tpl_name . "/conf.json");
//                $conf_arr = json_decode($conf_arr, true);
//
//                File::delete($destinationPath . "/" . $tpl_name . "/conf.json");
//
//                $obj = Template::where('slug', $conf_arr['slug'])->first();
//                if (!$obj) {
//                    $obj = new Template;
//                }
//                $obj->title = $conf_arr['title'];
//                $obj->folder_name = $tpl_name;
//
//                $obj->description = $conf_arr['description'];
//                $obj->slug = $conf_arr['slug'];
//                $obj->have_setting = $conf_arr['have_setting'];
//
//                $obj->type = $conf_arr['type'];
//                $obj->version = $conf_arr['version'];
//                $obj->author = $conf_arr['author'];
//                $obj->site = $conf_arr['site'];
//
//                $obj->save();
//
//                $variation = new TemplateVariations;
//                $variation->template_id = $obj->id;
//                if (isset($obj->title)) {
//                    $variation->variation_name = $obj->title . " Default Variation";
//                }
//                $variation->save();
//
//                if (isset($conf_arr['required_widgets']) && $conf_arr['required_widgets'] == 1) {
//                    $this->phelper->registerWidgets($destinationPath . "/" . $tpl_name, $obj->folder_name, $obj->id);
//                }
//
//                if (isset($conf_arr['required_forms']) && $conf_arr['required_forms'] == 1) {
//                    $this->phelper->registerForms($destinationPath . "/" . $tpl_name, $obj->folder_name);
//                }
//
//
//                if (isset($conf_arr['require_menu']) && $conf_arr['require_menu'] == 1) {
//                    $this->phelper->registerMenu($destinationPath . "/" . $tpl_name, $obj->folder_name);
//                }
//
//
//                File::delete($destinationPath . "/" . $name);
//            }
//        } else {
//            $this->helpers->updatesession('Upload Not Successfull!', 'alert-danger');
//        }
//    }

    /**
     * @param Request $request
     */
    public function postState(Request $request)
    {
        $req = $request->all();
        $id = $req['id'];
        $tpl = Template::find($id);
        if ($req['status'] == 'true') {
            $this->helpers->tplCreate($id);
        } else {
            $this->helpers->tplDelete($id);
        }
        $tpl->status = $req['status'];
        $tpl->save();
    }

    /**
     *
     */
    public function postEdit()
    {
        $id = Input::get('pk');
        $obj = Template::find($id);
        $obj->title = Input::get('value');
        $obj->save();
    }

    /**
     * @return View
     */
    public function getStart()
    {
        return view('uploads::gears.templates.tplsample');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function postStart(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|max:255|min:3',
                'author' => 'required|max:25',
                'description' => 'required|max:250|min:25',
                'version' => 'required'
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $req = $request->all();
            $folder = $this->phelper->makeTemplate($req);

            return response()->download($this->tmp_upload . $folder . '.zip');
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * @return string
     */
    /**
     * @return View
     */
    public function getNewindex()
    {
        $templates = Tpl::where('general_type', 'header')->run();
        return view('uploads::gears.hf._partials.new_templates', compact(['templates']));
    }

    protected function getDataTpl($id){
        $slug = explode('.', $id);
        $ui = Tpl::find($slug[0]);
        $variation = Tpl::findVariation($id);
        if (!$variation) return false;
        $settings = (isset($variation->settings) && $variation->settings) ? $variation->settings : [];
        return ['tpl'=>$ui,'variation'=>$variation,'settings'=>$settings];
    }
}
