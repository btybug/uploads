<?php namespace Btybug\Uploads\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Resources;
use Btybug\btybug\Models\ContentLayouts\ContentLayouts;
use Btybug\btybug\Models\Sections;
use Btybug\btybug\Services\CmsItemReader;
use Btybug\btybug\Services\CmsItemUploader;
use View;


/**
 * Class SectionsController
 * @package Btybug\Console\Http\Controllers
 */
class PageSectionsController extends Controller
{

    /**
     * @var null
     */
    private $helpers = null;
    /**
     * @var CmsItemUploader
     */
    private $upload;

    /**
     * SectionsController constructor.
     */
    public function __construct ()
    {
        $this->upload = new CmsItemUploader('page_sections');
    }

    /**
     * @param Request $request
     * @return View
     */
    public function getIndex (Request $request)
    {
        $slug = $request->get('p', 0);
        $currentPageSection = null;
        $pageSections = ContentLayouts::all();
        if ($slug) {
            $currentPageSection = ContentLayouts::find($slug);

        } else {
            if (count($pageSections)) {
                $currentPageSection = $pageSections[0];
            }
        }
        $variations = $currentPageSection ? $currentPageSection->variations() : [];

        return view('uploads::gears.page_sections.index', compact(['pageSections', 'currentPageSection', 'variations', 'type']));
    }

    public function getFrontend (Request $request)
    {
        $slug = $request->get('p', 0);
        $currentPageSection = null;
        $pageSections = ContentLayouts::all();
        if ($slug) {
            $currentPageSection = ContentLayouts::find($slug);

        } else {
            if (count($pageSections)) {
                $currentPageSection = $pageSections[0];
            }
        }
        $variations = $currentPageSection ? $currentPageSection->variations() : [];

        return view('uploads::gears.page_sections.index', compact(['pageSections', 'currentPageSection', 'variations', 'type']));
    }

    public function getVariations ($slug)
    {
        $pageSection = ContentLayouts::find($slug);
        if (! $pageSection) abort(404);
        $variations = $pageSection->variations();

        return view('uploads::gears.page_sections.variations', compact(['pageSection', 'variations']));
    }


    /**
     * @param $slug
     */
    public function getSettings ($slug, Request $request)
    {
        $settings = $request->all();
        if ($slug) {
            $view = ContentLayouts::renderLivePreview($slug, $settings);

            return $view ? $view : abort('404');
        } else {
            abort('404');
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSettings (Request $request)
    {
        $output = ContentLayouts::savePageSectionSettings($request->slug, $request->itemname, $request->except(['_token', 'itemname']), $request->save);

        return response()->json([
            'url'  => isset($output['id']) ? url('/admin/uploads/gears/page-sections/settings/' . $output['id']) : false,
            'html' => isset($output['data']) ? $output['data'] : false
        ]);
    }

    public function getConsole (Request $request)
    {
        return dd($request->except('_token'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteVariation (Request $request)
    {
        $result = false;
        if ($request->slug) {
            $result = ContentLayouts::deleteVariation($request->slug);
        }

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDelete (Request $request)
    {
        $slug = $request->get('slug');
        $pageSection = ContentLayouts::find($slug);
        if ($pageSection) {
            $deleted = $pageSection->delete();

            return \Response::json(['success' => $deleted, 'url' => url('/admin/uploads/gears/page-sections')]);
        }
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse|string
     */
    public function postUpload (Request $request)
    {
        return $this->upload->run($request, 'frontend');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postMakeActive (Request $request)
    {
        $data = $request->all();
        $result = false;
        if ($data['type'] == 'page_section') {
            ContentLayouts::active()->makeInActive()->save();
            $page_section = ContentLayouts::find($data['slug']);
            if ($page_section) $result = $page_section->setAttributes("active", true)->save() ? false : true;
            if (! ContentLayouts::activeVariation($data['slug'])) {
                $main = $page_section->variations()[0];
                $result = $main->setAttributes("active", true)->save() ? false : true;
            }
        } else if ($data['type'] == 'page_section_variation') {
            ContentLayouts::activeVariation($data['slug'])->makeInActiveVariation()->save();
            $pageSectionVariation = ContentLayouts::findVariation($data['slug']);
            $pageSectionVariation->setAttributes('active', true);
            $result = $pageSectionVariation->save() ? false : true;
        }

        return \Response::json(['error' => $result]);

    }
}



