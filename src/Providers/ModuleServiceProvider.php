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
                    'title' => 'Backend',
                    'url' => '/admin/uploads/gears',
                    'icon' => 'fa fa-cub'
                ],
                [
                    'title' => 'Frontend',
                    'url' => '/admin/uploads/gears/front-end',
                    'icon' => 'fa fa-cub'
                ],
            ],'upload_layouts' => [
                [
                    'title' => 'Backend',
                    'url' => '/admin/uploads/layouts',
                    'icon' => 'fa fa-cub'
                ],
                [
                    'title' => 'Frontend',
                    'url' => '/admin/uploads/layouts/front-end',
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
            ],
            'upload_assets' => [
                [
                    'title' => 'Js',
                    'url' => '/admin/uploads/assets/js',
                ],
                [
                    'title' => 'Css',
                    'url' => '/admin/uploads/assets/css',
                ],
                [
                    'title' => 'Fonts',
                    'url' => '/admin/uploads/assets/fonts',
                ]
            ],
            'upload_modules' => [
                [
                    'title' => 'Core Packages',
                    'url' => '/admin/uploads/modules',
                ],
                [
                    'title' => 'Extra Packages',
                    'url' => '/admin/uploads/modules/extra',
                ]
            ],
            'upload_apps' => [
                [
                    'title' => 'Core Apps',
                    'url' => '/admin/uploads/apps',
                ],
                [
                    'title' => 'Extra Apps',
                    'url' => '/admin/uploads/apps/extra',
                ]
            ],
            'upload_market' => [
                [
                    'title' => 'Market',
                    'url' => '/admin/uploads/market',
                ],
                [
                    'title' => 'Composer',
                    'url' => '/admin/uploads/composer',
                ]
            ]
        ];

        \Eventy::action('my.tab', $tubs);

        global $_PLUGIN_PROVIDERS;
//        dd($_PLUGIN_PROVIDERS);
        if (isset($_PLUGIN_PROVIDERS['pluginProviders'])) {
            foreach ($_PLUGIN_PROVIDERS['pluginProviders'] as $namespace => $options) {
                $this->app->register($namespace, $options['options'], $options['force']);
            }
        }
        //TODO; remove when finish all
     //   \Btybug\Cms\Models\Routes::registerPages('sahak.avatar/uploads');
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
