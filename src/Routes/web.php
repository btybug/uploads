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
Route::get('/', 'ModulesController@getChilds');

Route::get('/optimisation', function () {
    Artisan::call('plugin:optimaze');
    return redirect()->back()->with(['flash' => ['message' => 'modules optimisation successfully!!!']]);
});

Route::group(['prefix' => 'modules'], function () {
    Route::get('/', 'ModulesController@getIndex')->name('modules_index');
    Route::get('/{repository}/{package}/explore', 'ModulesController@getExplore');

    Route::group(['prefix' => 'extra'], function () {
        Route::get('/', 'PluginsController@getIndex')->name('plugins_index');
        Route::get('/{repository}/{package}/explore', 'PluginsController@getExplore');
    });
});

Route::group(['prefix' => 'composer'], function ($router) {
    Route::get('/', 'ComposerController@getIndex')->name('composer_index');
    Route::post('/main', 'ComposerController@getMain')->name('composer_main');
    Route::post('plugin-on-off', 'ComposerController@getOnOff')->name('on_off');
});

Route::group(['prefix' => 'market'], function ($router) {
    Route::get('/', 'MarketController@getIndex')->name('composer_market');
});

Route::group(['prefix' => 'apps'], function ($router) {
    Route::get('/', 'AppsController@getIndex')->name('app_plugins');
    Route::get('/{repository}/{package}/explore', 'AppsController@getExplore');

    Route::group(['prefix' => 'extra'], function () {
        Route::get('/', 'AppsController@getExtra')->name('app_extra');
        Route::get('/{repository}/{package}/explore', 'AppsController@getExplore');
    });
});

Route::group(['prefix' => 'assets'], function () {
    Route::get('/', 'AssetsController@getIndex');
    Route::get('/js', 'AssetsController@getJs');
    Route::get('/css', 'AssetsController@getCss');
    Route::get('/fonts', 'AssetsController@getFonts');

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

Route::group(['prefix' => 'gears'], function () {
    Route::get('/', 'UnitsController@getIndex');
    Route::get('/front-end', 'UnitsController@getFrontend');

    Route::post('/upload', 'UnitsController@postUploadUnit');
    Route::get('/delete-variation/{slug}', 'UnitsController@postDeleteVariation');
    Route::get('/units-variations/{slug}', 'UnitsController@getUnitVariations');
    Route::post('/units-variations/{slug}', 'UnitsController@postUnitVariations');

    Route::get('/live-settings/{slug}', 'UnitsController@unitPreview');

    Route::get('/settings/{slug?}', 'UnitsController@getSettings');
    Route::get('/settings-iframe/{slug}/{settings?}', 'UnitsController@unitPreviewIframe');
    Route::post('/settings/{id}/{save?}', 'UnitsController@postSettings');
    Route::post('/delete', 'UnitsController@postDelete');
});

Route::group(['prefix' => 'layouts'], function () {
    Route::get('/', 'PageSectionsController@getIndex');
    Route::get('/front-end', 'PageSectionsController@getFrontend');
    Route::get('/settings/{slug}', 'PageSectionsController@getSettings');
    Route::get('/variations/{slug}', 'PageSectionsController@getVariations');
    Route::post('/settings/{slug}/{save?}', 'PageSectionsController@postSettings');
    Route::post('/console', 'PageSectionsController@getConsole');
    Route::post('/make-active', 'PageSectionsController@postMakeActive');
    Route::post('/upload', 'PageSectionsController@postUpload');
    Route::get('/delete-variation/{slug}', 'PageSectionsController@postDeleteVariation');
    Route::post('/delete', 'PageSectionsController@postDelete');
});


Route::group(['prefix' => 'gears1'], function () {
    Route::get('/', 'GearsController@getIndex');

    Route::group(['prefix' => 'units'], function () {
        Route::get('/', 'UnitsController@getIndex');
        Route::post('/upload', 'UnitsController@postUploadUnit');
        Route::get('/delete-variation/{slug}', 'UnitsController@postDeleteVariation');
        Route::get('/test/{slug?}', 'UnitsController@test');
        Route::get('/settings/{slug?}', 'UnitsController@getSettings');
        Route::get('/settings-iframe/{slug}/{settings?}', 'UnitsController@unitPreviewIframe');
        Route::post('/settings/{id}/{save?}', 'UnitsController@postSettings');
        Route::post('/delete', 'UnitsController@postDelete');
    });
    Route::get('/layouts', 'LayoutController@getIndex');
    Route::get('/back-end', 'GearsController@getBackend');
    Route::get('/front-end', 'GearsController@getFrontend');
    Route::get('/gears-variations/{slug}', 'GearsController@getUiVariations');
    Route::post('/gears-variations/{slug}', 'GearsController@postUiVariations');
    Route::post('/widget-with-type', 'GearsController@postUiWithType');
    Route::post('/upload-widget', 'GearsController@postUploadUi');
    Route::get('/settings/{slug}', 'GearsController@getSettings');
    Route::get('/settings-live/{slug}', 'GearsController@widgetPerview');
    Route::get('/settings-iframe/{slug}/{settings?}', 'GearsController@widgetPerviewIframe');
    Route::post('/settings-iframe/{id}/{save?}', 'GearsController@postSettings');
    Route::post('/settings/{id}/{save?}', 'GearsController@postSettings');
    Route::post('/delete', 'GearsController@postDelete');
    Route::any('/delete-variation/{slug}', 'GearsController@getDeleteVariation');
    Route::any('/make-default/{slug}', 'GearsController@getMakeDefault');
    Route::any('/make-default-variation/{slug}', 'GearsController@getDefaultVariation');
});

Route::group(['prefix' => 'assets'], function () {
    Route::get('/aaa', 'AssetsController@getIndex');
    Route::group(['prefix' => 'bbb'], function () {
        Route::get('/ccc', 'AssetsController@getIndex');
    });
});

