<?php
/**
 * Copyright (c) 2017. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 */

namespace Btybug\Uploads\Services;

use Btybug\btybug\Services\GeneralService;
use Btybug\Framework\Repository\VersionsRepository;
use Btybug\btybug\Repositories\AdminsettingRepository;

/**
 * Class ThemeService
 * @package Btybug\Console\Services
 */
class SettingsService extends GeneralService
{

    /**
     * @var
     */
    private $current;
    private $versionsRepository;
    private $adminsettingRepository;
    /**
     * @var
     */
    private $result;

    public function __construct(VersionsRepository $versionsRepository, AdminsettingRepository $adminsettingRepository)
    {
        $this->versionsRepository = $versionsRepository;
        $this->adminsettingRepository = $adminsettingRepository;
    }

    public function makeJquery($request, $section)
    {
        if ($request->file('jquery_version')) {
            $this->exstension = $request->file('jquery_version')->getClientOriginalExtension(); // getting image extension
            $oname = $request->file('jquery_version')->getClientOriginalName(); // getting image extension
            $fname = 'Jquery' . ucfirst($section) . "." . $this->exstension;
            $request->file('jquery_version')->move(public_path('js/versions'), $fname);
            return '/js/versions/' . $fname;
        } else {
            return $request->get('jquery_version');
        }

    }

    public function MakeMainJS($section, $data)
    {
        \File::put(public_path("js/" . $section . ".js"), $data);
    }
}