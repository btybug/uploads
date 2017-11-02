<?php

namespace Btybug\Uploads\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/Lang', 'uploads');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'uploads');
        $tubs = [
            'frontend_gears' => [

                [
                    'title' => 'H&F',
                    'url' => '/admin/uploads/gears/h-f',
                    'icon' => 'fa fa-cub'
                ],
                [
                    'title' => 'Page Layouts',
                    'url' => '/admin/uploads/gears/page-sections',
                    'icon' => 'fa fa-cub'
                ],
                [
                    'title' => 'Sections',
                    'url' => '/admin/uploads/gears/sections',
                    'icon' => 'fa fa-cub'
                ],
                [
                    'title' => 'Main Content',
                    'url' => '/admin/uploads/gears/main-body',
                    'icon' => 'fa fa-cub'
                ],
                [
                    'title' => 'Units',
                    'url' => '/admin/uploads/gears/units',
                    'icon' => 'fa fa-cub'
                ],
                [
                    'title' => 'Component',
                    'url' => '/admin/uploads/gears/component',
                    'icon' => 'fa fa-cub'
                ]
            ],

            'upload_gears' => [
                [
                    'title' => 'Frontend widgets',
                    'url' => '/admin/uploads/gears/front-end',
                    'icon' => 'fa fa-cub'
                ],
                [
                    'title' => 'Backend widgets ',
                    'url' => '/admin/uploads/gears/back-end',
                    'icon' => 'fa fa-cub'
                ],
            ], 'uploads_assets' => [
                [
                    'title' => 'Profiles',
                    'url' => '/admin/uploads/assets/profiles',
                    'icon' => 'fa fa-cub'
                ],
                [
                    'title' => 'Styles',
                    'url' => '/admin/uploads/assets/styles',
                    'icon' => 'fa fa-cub'
                ],
                [
                    'title' => 'Files',
                    'url' => '/admin/uploads/assets/files',
                    'icon' => 'fa fa-cub'
                ],
            ]
        ];
        \Eventy::action('my.tab', $tubs);

        //TODO; remove when finish all
     //   \Sahakavatar\Cms\Models\Routes::registerPages('sahak.avatar/uploads');
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
