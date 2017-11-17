<?php namespace Btybug\Uploads\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Resources;
use Btybug\btybug\Models\Sections;
use Btybug\btybug\Services\CmsItemReader;
use Btybug\btybug\Services\CmsItemUploader;
use Btybug\Modules\Models\AdminPages;
use View;


class SectionsController extends Controller
{

    /**
     * @var null
     */
    private $helpers = null;
    private $upload;

    /**
     * SectionsController constructor.
     */
    public function __construct()
    {
        $this->upload = new CmsItemUploader('sections');
    }

    /**
     * @param Request $request
     * @return View
     */
    public function getIndex(Request $request)
    {
        $slug = $request->get('p');
        $type = $request->get('type', 'horizontal');
        $types = Sections::getTypes();
        $variations = [];
        $current = null;

        $sections = CmsItemReader::getAllGearsByType('sections')
            ->where('place', 'frontend')
            ->where('type', $type)
            ->run();

        if (count($sections) && $slug) {
            $current = CmsItemReader::getAllGearsByType('sections')
                ->where('place', 'frontend')
                ->where('type', $type)
                ->where('slug', $slug)
                ->first();
        } elseif (count($sections)) {
            $current = CmsItemReader::getAllGearsByType('sections')
                ->where('place', 'frontend')
                ->where('type', $type)
                ->first();
        }

        $variations = $current ? $current->variations() : [];
        return view("uploads::gears.sections.index", compact(['types', 'unit', 'type', 'variations', 'sections', 'current']));
    }


    public function getSettings(Request $request)
    {
        if ($request->slug) {
            $view = Sections::renderLivePreviewFrontend($request->slug);
            return $view ? $view : abort('404');
        } else {
            abort('404');
        }
    }

    public function postSettings(Request $request)
    {
        $output = Sections::saveSettings($request->id, $request->itemname, $request->except(['_token', 'itemname']), $request->save);
        $result = $output ? ['html' => $output['html'], 'url' => url('/admin/uploads/gears/sections/settings', ['slug' => $output['slug']]), 'error' => false] : ['error' => true];
        return \Response::json($result);
    }

    public function postDeleteVariation(Request $request)
    {
        $result = false;
        if ($request->slug) {
            $result = Sections::deleteVariation($request->slug);
        }
        return \Response::json(['success' => $result]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDelete(Request $request)
    {
        $slug = $request->get('slug');
        $section = CmsItemReader::getAllGearsByType('sections')
            ->where('place', 'frontend')
            ->where('slug', $slug)
            ->first();
        if ($section) {
            $deleted = $section->deleteGear();
            return \Response::json(['success' => $deleted, 'url' => url('/admin/uploads/sections/main-body')]);
        }
    }

    /**
     * @param $id
     * @param null $type
     * @return View
     */
    public function unitPreviewIframe($id, $type = null)
    {
        if (!$id) {
            abort('404');
        }
        $slug = explode('.', $id);
        $section = Sections::find($slug[0]);
        $variation = Sections::findVariation($id);
        $settings = (isset($variation->settings) && $variation->settings) ? $variation->settings : [];
        $extra_data = 'some string';
        if (isset($section->main_type) && $section->main_type == 'data_source') {
            $extra_data = BBGiveMe('array', 3);
        }
        $htmlBody = $section->render(['settings' => $settings, 'source' => $extra_data, 'cheked' => 1, 'field' => null]);
        $htmlSettings = $section->renderSettings(compact('settings'));
        $settings_json = json_encode($settings, true);
        return view('uploads::gears.sections._partials.section_preview', compact(['htmlBody', 'htmlSettings', 'settings', 'settings_json', 'id', 'section']));
    }

    public function postUpload(Request $request)
    {
        return $this->upload->run($request, 'frontend');
    }
}



