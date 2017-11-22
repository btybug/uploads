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
            'upload_gears' => [
                [
                    'title' => 'Backend',
                    'url' => '/admin/uploads/gears/back-end',
                    'icon' => 'fa fa-cub'
                ],
                [
                    'title' => 'Frontend',
                    'url' => '/admin/uploads/gears/front-end',
                    'icon' => 'fa fa-cub'
                ],
            ], 'upload_layouts' => [
                [
                    'title' => 'Backend',
                    'url' => '/admin/uploads/layouts/back-end',
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
                    'url' => '/admin/uploads/modules/core-packages',
                ],
                [
                    'title' => 'Extra Packages',
                    'url' => '/admin/uploads/modules/extra-packages',
                ]
            ],
            'upload_apps' => [
                [
                    'title' => 'Core Apps',
                    'url' => '/admin/uploads/apps/core-apps',
                ],
                [
                    'title' => 'Extra Apps',
                    'url' => '/admin/uploads/apps/extra-apps',
                ]
            ],
            'upload_market' => [
                [
                    'title' => 'Market',
                    'url' => '/admin/uploads/market/packages',
                ],
                [
                    'title' => 'Composer',
                    'url' => '/admin/uploads/market/composer',
                ]
            ]
        ];

        \Eventy::action('my.tab', $tubs);
        \Eventy::action('admin.menus', [
            "title" => "Uploads",
            "custom-link" => "#",
            "icon" => "fa fa-smile-o",
            "children" => [
                [
                    "title" => "Modules",
                    "custom-link" => "/admin/uploads/modules/core-packages",
                    "icon" => "fa fa-angle-right",
                    'children' => [
                        [
                            "title" => "Core packages",
                            "icon" => "fa fa-angle-right",
                            'custom-link' => '/admin/uploads/modules/core-packages'
                        ], [
                            "title" => "Extra packages",
                            "icon" => "fa fa-angle-right",
                            'custom-link' => '/admin/uploads/modules/extra-packages'
                        ]
                    ]
                ],
                [
                    "title" => "Apps",
                    "custom-link" => "/admin/uploads/apps",
                    "icon" => "fa fa-angle-right",
                    'children' => [
                        [
                            "title" => "Core apps",
                            "icon" => "fa fa-angle-right",
                            'custom-link' => '/admin/uploads/apps/core-apps'
                        ], [
                            "title" => "Extra apps",
                            "icon" => "fa fa-angle-right",
                            'custom-link' => '/admin/uploads/apps/extra-apps'
                        ]
                    ]
                ],
                [
                    "title" => "Layouts",
                    "custom-link" => "/admin/uploads/layouts",
                    "icon" => "fa fa-angle-right",
                    'children' => [
                        [
                            "title" => "Backend",
                            "icon" => "fa fa-angle-right",
                            'custom-link' => '/admin/uploads/layouts/back-end'
                        ], [
                            "title" => "Frontend",
                            "icon" => "fa fa-angle-right",
                            'custom-link' => '/admin/uploads/layouts/front-end'
                        ]

                    ]
                ],
                [
                    "title" => "Market",
                    "custom-link" => "/admin/uploads/market",
                    "icon" => "fa fa-angle-right",
                    'children' => [
                        [
                            "title" => "Packages",
                            "icon" => "fa fa-angle-right",
                            'custom-link' => '/admin/uploads/market/packages'
                        ], [
                            "title" => "Composer",
                            "icon" => "fa fa-angle-right",
                            'custom-link' => '/admin/uploads/market/composer'
                        ]
                    ]
                ],[
                    "title" => "Gears",
                    "custom-link" => "/admin/uploads/gears",
                    "icon" => "fa fa-angle-right",
                    'children' => [
                        [
                            "title" => "Backend",
                            "icon" => "fa fa-angle-right",
                            'custom-link' => '/admin/uploads/gears/back-end'
                        ], [
                            "title" => "Frontend",
                            "icon" => "fa fa-angle-right",
                            'custom-link' => '/admin/uploads/gears/front-end'
                        ]
                    ]
                ]
            ]]);


        global $_PLUGIN_PROVIDERS;
//        dd($_PLUGIN_PROVIDERS);
        if (isset($_PLUGIN_PROVIDERS['pluginProviders'])) {
            foreach ($_PLUGIN_PROVIDERS['pluginProviders'] as $namespace => $options) {
                $this->app->register($namespace, $options['options'], $options['force']);
            }
        }
        //TODO; remove when finish all
        //   \Btybug\btybug\Models\Routes::registerPages('sahak.avatar/uploads');
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
