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

use Sahakavatar\Cms\Helpers\helpers;
use App\Http\Controllers\Controller;
use Sahakavatar\Uploads\Models\Profiles;
use Sahakavatar\Uploads\Models\StyleItems;
use Sahakavatar\Uploads\Models\Styles;
use Datatables;
use File,View,Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    public $helper;

    public function __construct ()
    {
        $this->helper = new helpers();
    }

    public function getIndex ()
    {
        $profiles = Profiles::all();

        return view('uploads::assets.profile.list',compact(['profiles']));
    }

    public function getEdit ($id,Request $request)
    {
        $type = $request->get('type','text');
        $profile = Profiles::find($id);
        $p = null;
        if(! $profile) return redirect()->back();

        $styles = Styles::where('type', $type)->get();
        if(count($styles)){
            $p = $request->get('p',$styles[0]->slug);
        }
        return view('uploads::assets.profile.edit',compact(['id','profile','styles','type','p']));
    }

    public function postDefault (Request $request)
    {
        $data = $request->all();

        $profile = Profiles::find($data['profile_id']);

        if(isset($data['default_style'])){
            $profile->styles()->detach($data['default_style']);
        }

        $profile->styles()->attach($data['style_id']);

        return \Response::json(['error' => false]);
    }

    /**
     * @param $style_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getStylePreview ($id,$style_id)
    {
        $profile = Profiles::find($id);
        $style = Styles::find($style_id);

        if (! $style && ! $profile) return redirect()->back();

        $items = $style->items;
        $default = $profile->styles()->where('style_id',$style_id)->first();

        return view('uploads::assets.profile.style_preview')->with(['profile' => $profile,'style' => $style, 'styleItems' => $items,'default' => $default]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postRenderStyles (Request $request)
    {
        $main_type = $request->get('main_type');
        $subItem = $request->get('sub');
        $sort = $request->get('sort');
        $id = $request->get('profile_id');
        $orderData = $this->helper->sorting($sort);

        if ((int)$subItem == false) {
            $styles = Styles::where('type', $main_type)->get();
            $html = View::make("uploads::assets.profile._partials.subs_list", compact(['id','styles', 'subItem', 'main_type', 'sort']))->render();
        } else {
            $style = Styles::find($subItem);
            if (! $style) return \Response::json(['message' => "Sub Style not found !!!", 'error' => true]);
            $styleItems = $style->items()->orderBy($orderData['key'], $orderData['value'])->get();

            $html = View::make("uploads::assets.profile._partials.list_cube", compact(['id','styleItems', 'subItem', 'main_type', 'sort']))->render();
        }


        return \Response::json(['html' => $html, 'subItem' => $subItem, 'error' => false]);
    }

    public function postActivate (Request $request)
    {
        $id = $request->get('id');

        $profile = Profiles::find($id);

        if(! $profile) return \Response::json(['error' => true]);

        Profiles::where('is_default',1)->update(['is_default' => 0]);

        if($profile->update(['is_default' => 1])) return \Response::json(['error' => false]);

        return \Response::json(['error' => true]);
    }

    public function postCreate (Request $request)
    {
        $name = $request->get('name');

        $validator = Validator::make(
            $request->all(), [
                'name' => 'required'
            ]
        );

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        Profiles::create(['name' => $name,'user_id' => Auth::id()]);

        return redirect()->back();
    }

    public function postDelete(Request $request){
        $id = $request->get('id');

        $profile = Profiles::find($id);

        if(! $profile) return \Response::json(['error' => true]);

        if($profile->is_default) return \Response::json(['error' => true,'message' => 'You can not delete default profile']);

        $profile->delete();

        return \Response::json(['error' => false]);
    }
}