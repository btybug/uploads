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

use Sahakavatar\Cms\Services\CmsItemReader;
use Sahakavatar\Cms\Services\CmsItemUploader;
use Sahakavatar\Cms\Helpers\helpers;
use App\Http\Controllers\Controller;
use Sahakavatar\Cms\Models\ContentLayouts\ContentLayouts;
use Sahakavatar\Cms\Models\ContentLayouts\MainBody;
use Sahakavatar\Cms\Models\Templates;
use Sahakavatar\Cms\Models\Widgets;
use File;
use Illuminate\Http\Request;

/**
 * Class ModulesController
 * @package Sahakavatar\Modules\Http\Controllers
 */
class MainBodyController extends Controller
{
    /**
     * @var helpers
     */
    public $helper;
    /**
     * @var Module
     */
    protected $modules;

    /**
     * ModulesController constructor.
     * @param Module $module
     * @param Upload $upload
     * @param validateUpl $v
     */
    public function __construct()
    {
        $this->helper = new helpers();
        $this->upload = new CmsItemUploader('main_body');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     **/

    public function getIndex(Request $request)
    {
        $slug = $request->get('p', 0);
        $type = $request->get('type', 'general');
        $currentMainBody = null;

        $mainBodies = CmsItemReader::getAllGearsByType('main_body')
            ->where('place', 'frontend')
            ->where('type', $type)
            ->run();
        if ($slug) {
            $currentMainBody = CmsItemReader::getAllGearsByType('main_body')
                ->where('place', 'frontend')
                ->where('type', $type)
                ->where('slug', $slug)
                ->first();
        } else {
            if (count($mainBodies)) {
                $currentMainBody = CmsItemReader::getAllGearsByType('main_body')
                    ->where('place', 'frontend')
                    ->where('type', $type)
                    ->first();
            }
        }

        $variations = $currentMainBody ? $currentMainBody->variations() : [];

        return view('uploads::gears.main_body.index',compact(['mainBodies', 'currentMainBody', 'variations', 'type']));
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse|string
     */
    public function postUpload(Request $request)
    {
        return $this->upload->run($request,'frontend');
    }

    /**
     * @param $slug
     */
    public function getSettings($slug)
    {
        if ($slug) {
            $view = MainBody::renderLivePreview($slug);
            return $view ? $view : abort('404');
        } else {
            abort('404');
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSettings(Request $request)
    {
        $output = ContentLayouts::savePageSectionSettings($request->id, $request->itemname, $request->except(['_token', 'itemname']), $request->save);
        return response()->json([
            'url' => isset($output['id']) ? url('/admin/uploads/gears/main-body/settings/' . $output['id']) : false,
            'html' => isset($output['data']) ? $output['data'] : false

        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteVariation(Request $request) {
        $result = false;
        if($request->slug) {
            $result = ContentLayouts::deleteVariation($request->slug);
        }
        return \Response::json(['success' => $result]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDelete(Request $request) {
        $slug = $request->get('slug');
        $mainBody = CmsItemReader::getAllGearsByType('main_body')
            ->where('place', 'frontend')
            ->where('slug', $slug)
            ->first();
        if($mainBody) {
            $deleted = $mainBody->deleteGear();
            return \Response::json(['success' => $deleted, 'url' => url('/admin/uploads/gears/main-body')]);
        }
    }


}
