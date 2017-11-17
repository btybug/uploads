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
use App\Models\Themes\Themes;
use Illuminate\Http\Request;
use Btybug\btybug\Helpers\helpers;
use Btybug\btybug\Models\ContentLayouts\ContentLayouts;

/**
 * Class ModulesController
 * @package Btybug\Modules\Http\Controllers
 */
class LayoutController extends Controller
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
    }


    public function getIndex(Request $request)
    {
        $p = $request->get('p', null);
        $type = 'main_body';
        $contentLayouts = ContentLayouts::findByType('main_body');

        if ($p) {
            $curentLayout = ContentLayouts::find($p);
        } elseif (count($contentLayouts)) {
            $curentLayout = $contentLayouts[0];
        }
        $variations = $curentLayout->variations();
        return view('uploads::gears.layouts.index', compact(['contentLayouts', 'curentLayout', 'variations']));
    }

}
