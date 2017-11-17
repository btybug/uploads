<?php namespace Btybug\Uploads\Http\Controllers;

use App\Http\Controllers\Controller;
use File;
use Illuminate\Http\Request;
use Resources;
use Btybug\btybug\Models\ContentLayouts\ContentLayouts;
use Btybug\btybug\Models\Templates\Units;
use Btybug\btybug\Services\CmsItemReader;
use Btybug\btybug\Services\CmsItemUploader;
use Btybug\Resources\Models\Validation as validateUpl;
use View;


class UnitsController extends Controller
{

    private $helpers = null;

    private $up;

    private $tp;

    private $types;

    private $upload;

    public function __construct(validateUpl $validateUpl)
    {
        $this->upload = new CmsItemUploader('units');
        $this->validateUpl = new $validateUpl;
        $this->up = config('paths.ui_elements_uplaod');
        $this->tp = config('paths.units_path');
        //$this->unitTypes = $this->types = @json_decode(File::get(config('paths.unit_path') . 'configTypes.json'), 1)['types'];
    }

    public function getIndex(Request $request)
    {
        $units = Units::all()->run();
        return view("uploads::gears.units.index", compact(['units']));
    }

    public function getFrontend(Request $request)
    {
        $units = Units::all()->run();
        return view("uploads::gears.units.index", compact(['units']));
    }

    public function getUnitVariations($slug)
    {
        $unit = Units::find($slug);
        if (!count($unit)) return redirect()->back();
        $variation = [];
        $variations = $unit->variations();
        return view('uploads::gears.units.variations', compact(['unit', 'variations', 'variation']));
    }


    public function postUnitWithType(Request $request)
    {
        $main_type = $request->get('main_type');
        $general_type = $request->get('type', null);

        if ($general_type) {
            $ui_units = Units::getAllUnits()->where('main_type', $main_type)->where('type', $general_type)->run();
        } else {
            $ui_units = Units::getAllUnits()->where('type', $main_type)->run();
        }

        $html = View::make('resources::units._partials.list_cube', compact(['ui_units']))->render();

        return \Response::json(['html' => $html, 'error' => false]);
    }

    public function postUnitVariations(Request $request, $slug)
    {
        $ui = Units::find($slug);
        if (!$ui) return redirect()->back();
        $ui->makeVariation($request->except('_token', 'ui_slug'))->save();

        return redirect()->back();
    }

    public function postUploadUnit(Request $request)
    {
        return $this->upload->run($request, 'frontend');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteVariation(Request $request)
    {
        $result = false;
        if ($request->slug) {
            $result = Units::deleteVariation($request->slug);
        }
        return redirect()->back()->with("message","Variation was deleted");
    }

    public function postDelete(Request $request)
    {
        $slug = $request->get('slug');
        $unit =  Units::find($slug);
        if ($unit) {
            $deleted = $unit->delete();
            return \Response::json(['success' => $deleted, 'url' => url('/admin/uploads/gears/units')]);
        }
    }

    public function getSettings(Request $request)
    {
        if ($request->slug) {
            $view = Units::renderLivePreview($request->slug, 'frontend');
            return $view ? $view : abort('404');
        } else {
            abort('404');
        }
    }

    public function unitPreview($id)
    {
        $slug = explode('.', $id);
        $ui = Units::find($slug[0]);
        $variation = Units::findVariation($id);

        if (!$variation) return redirect()->back();

        $ifrem = [];
        $settings = (isset($variation->settings) && $variation->settings) ? $variation->settings : [];

        $ifrem['body'] = url('/admin/uploads/gears/settings-iframe', $id);
        $ifrem['settings'] = url('/admin/uploads/gears/settings-iframe', $id) . '/settings';

        return view('uploads::gears.preview', compact(['ui', 'id', 'ifrem', 'settings']));

    }

    public function unitPreviewIframe($id, $type = null)
    {
        $slug = explode('.', $id);
        $ui = Units::find($slug[0]);
        $_this = $ui;
        $variation = Units::findVariation($id);
//        if (!$variation) return redirect()->back();
        $settings = (isset($variation->settings) && $variation->settings) ? $variation->settings : [];
        $extra_data = 'some string';
        if ($ui->main_type == 'data_source') {
            $extra_data = BBGiveMe('array', 3);
        }
        $htmlBody = $ui->renderLive(['settings' => $settings, 'source' => $extra_data, 'cheked' => 1, 'field' => null]);
        $htmlSettings = $ui->renderSettings(compact('settings'));
        $settings_json = json_encode($settings, true);
        return view('uploads::gears.units._partials.unit_preview', compact(['htmlBody', 'htmlSettings', 'settings', 'settings_json', 'id', 'ui']));
    }

    public function postSettings(Request $request)
    {
        $output = Units::saveSettings($request->id, $request->itemname, $request->except(['_token', 'itemname']), $request->save);

        return response()->json([
            'error' => $output ? false : true,
            'url' => $output ? url('/admin/uploads/gears/settings/' . $output['slug']) : false,
            'html' => $output ? $output['html'] : false
        ]);
    }

    public function getDefaultVariation($id)
    {
        $data = explode('.', $id);
        $unit = Units::find($data[0]);

        if (!empty($data) && $unit) {
            foreach ($unit->variations() as $variation) {
                $variation->setAttributes('default', 0);
                $variation->save();
            }

            $variation = Units::findVariation($id);
            $variation->setAttributes('default', 1);
            $variation->save();

            return redirect()->back()->with('message', 'New Default variation is ' . $variation->title);
        }

        return redirect()->back();
    }

    public function getMakeDefault($slug)
    {
        $units = Units::getAllUnits()->where('type', 'fields')->run();
        if (count($units)) {
            foreach ($units as $unit) {
                if ($unit->slug == $slug) {
                    $default = $unit->title;
                    $unit->setAttributes('default', 1);
                } else {
                    $unit->setAttributes('default', 0);
                }
                $unit->save();
            }
            return redirect()->back()->with('message', 'New Default Unit is ' . $default);
        }

        return redirect()->back();
    }
}



