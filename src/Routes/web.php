<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Route::get('/', 'ModulesController@getChilds',true);

//Route::get('/optimisation', function () {
//    Artisan::call('plugin:optimaze');
//    return redirect()->back()->with(['flash' => ['message' => 'modules optimisation successfully!!!']]);
//});

Route::group(['prefix' => 'modules'], function () {
    Route::get('/', 'ModulesController@getIndex',true)->name('modules_index');
//    Route::get('/', 'ModulesController@getIndex')->name('modules_index');
    Route::get('/{repository}/{package}/explore', 'ModulesController@getExplore',true);

    Route::group(['prefix' => 'extra'], function () {
        Route::get('/', 'PluginsController@getIndex',true)->name('plugins_index');
        Route::get('/{repository}/{package}/explore', 'PluginsController@getExplore',true);
    });
});

Route::group(['prefix' => 'apps'], function ($router) {
    Route::get('/', 'AppsController@getIndex',true)->name('app_plugins');
    Route::get('/{repository}/{package}/explore', 'AppsController@getExplore',true);

    Route::group(['prefix' => 'extra'], function () {
        Route::get('/', 'AppsController@getExtra',true)->name('app_extra');
        Route::get('/{repository}/{package}/explore', 'AppsController@getExplore',true);
    });
});

Route::group(['prefix' => 'gears'], function () {
    Route::get('/', function () {
        return view("uploads::gears.units.list");
    },true);
    Route::get('/back-end', 'UnitsController@getIndex',true);
    Route::get('/front-end', 'UnitsController@getFrontend',true);
    Route::post('/upload', 'UnitsController@postUploadUnit');
    Route::get('/delete-variation/{slug}', 'UnitsController@postDeleteVariation');
    Route::get('/units-variations/{slug}', 'UnitsController@getUnitVariations',true);
    Route::post('/units-variations/{slug}', 'UnitsController@postUnitVariations');
    Route::get('/live-settings/{slug}', 'UnitsController@unitPreview',true);
    Route::get('/settings/{slug?}', 'UnitsController@getSettings',true);
    Route::get('/settings-iframe/{slug}/{settings?}', 'UnitsController@unitPreviewIframe',true);
    Route::post('/settings/{id}/{save?}', 'UnitsController@postSettings');
    Route::post('/delete', 'UnitsController@postDelete');
});

Route::group(['prefix' => 'layouts'], function () {
    Route::get('/', function () {
        return view("uploads::gears.page_sections.list");
    },true);
    Route::get('/back-end', 'PageSectionsController@getIndex',true);
    Route::get('/front-end', 'PageSectionsController@getFrontend',true);
    Route::get('/settings/{slug}', 'PageSectionsController@getSettings',true);
    Route::get('/variations/{slug}', 'PageSectionsController@getVariations',true);
    Route::post('/settings/{slug}/{save?}', 'PageSectionsController@postSettings');
    Route::post('/console', 'PageSectionsController@getConsole');
    Route::post('/make-active', 'PageSectionsController@postMakeActive');
    Route::post('/upload', 'PageSectionsController@postUpload');
    Route::get('/delete-variation/{slug}', 'PageSectionsController@postDeleteVariation');
    Route::post('/delete', 'PageSectionsController@postDelete');
});

Route::group(['prefix' => 'assets'], function () {
    Route::get('/', 'AssetsController@getIndex',true);
    Route::get('/js', 'AssetsController@getJs',true);
    Route::get('/css', 'AssetsController@getCss',true);
    Route::get('/fonts', 'AssetsController@getFonts',true);

    Route::post('/', 'AssetsController@postUploadJs');
    Route::post('/change-version', 'AssetsController@postChangeVersion');
    Route::post('/update-link', 'AssetsController@postUpdateLink');
    Route::post('/get-versions', 'AssetsController@getVersions');
    Route::post('/get-active-versions', 'AssetsController@getActiveVersions');
    Route::post('/upload-version', 'AssetsController@postUploadVersion');
    Route::post('/generate-main-js', 'AssetsController@postGenerateMainJs');
    Route::post('/make-active', 'AssetsController@postMakeActive');
    Route::post('/css', 'AssetsController@postCss');
    Route::post('/delete', 'AssetsController@delete');
});

Route::group(['prefix' => 'market'], function ($router) {
    Route::get('/', 'MarketController@getIndex',true)->name('composer_market');
    Route::group(['prefix' => 'composer'], function ($router) {
        Route::get('/', 'ComposerController@getIndex',true)->name('composer_index');
        Route::post('/main', 'ComposerController@getMain')->name('composer_main');
        Route::post('plugin-on-off', 'ComposerController@getOnOff')->name('on_off');
    });
});
