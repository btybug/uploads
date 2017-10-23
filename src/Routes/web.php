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
Route::get('/', 'ModulesController@getIndexUploads');
Route::get('/optimisation', function () {
    Artisan::call('plugin:optimaze');
    return redirect()->back()->with(['flash' => ['message' => 'modules optimisation successfully!!!']]);
});
Route::group(['prefix' => 'assets'], function () {
    Route::get('/', 'AssetsController@getIndex');
    Route::group(['prefix' => 'profiles'], function () {
        Route::get('/', 'ProfileController@getIndex');
        Route::post('/make-active', 'ProfileController@postActivate');
        Route::post('/create', 'ProfileController@postCreate');
        Route::post('/delete', 'ProfileController@postDelete');
        Route::post('/render-styles', 'ProfileController@postRenderStyles');
        Route::group(['prefix' => 'edit'], function () {
            Route::post('/default', 'ProfileController@postDefault');
            Route::group(['prefix' => '{id}'], function () {
                Route::get('/', 'ProfileController@getEdit');
                Route::get('/style_preview/{style_id}', 'ProfileController@getStylePreview');
                Route::get('/style_preview/{style_id}', 'ProfileController@getStylePreview');
            });
        });
    });
    Route::group(['prefix' => 'files'], function () {
        Route::get('/', 'FilesController@getIndex');
        Route::post('/files-with-type', 'FilesController@postFilesWithType');
        Route::post('/upload', 'FilesController@postUpload');
    });
    Route::group(['prefix' => 'styles'], function () {
        Route::get('/', 'StylesController@getIndex');
        Route::post('/upload', 'StylesController@postUpload');
        Route::get('/optimize', 'StylesController@getOptimize');
        Route::post('/render-styles', 'StylesController@postRenderStyles');
        Route::post('/show_popup', 'StylesController@postShowPopUp');
        Route::get('/create-sub/{type}', 'StylesController@getCreateSub');
        Route::get('/delete/{id}', 'StylesController@getStyleDelete');
        Route::post('/add-sub', 'StylesController@postAddSub');
        Route::get('/make-default/{style_id}/{id}', 'StylesController@makeDefault');
        Route::get('/style_preview/{type}/{style_id}', 'StylesController@getStylePreview');
        Route::post('/style_preview/{type}/{style_id}', 'StylesController@postStylePreview');
        Route::post('/style_preview/css', 'StylesController@postStyleCssUpdate');
    });
});
Route::group(['prefix' => 'modules'], function () {
    Route::get('/', 'ModulesController@getIndex');
    Route::get('/test', 'ModulesController@getTest');
    Route::post('/upload', 'ModulesController@postUpload');
});
Route::group(['prefix' => 'units'], function () {
    Route::get('/', 'UnitsController@getIndex');
    Route::get('/backend', 'UnitsController@getIndex');
    Route::get('/frontend', 'UnitsController@getFrontend');
    Route::post('/unit-with-type', 'UnitsController@postUnitWithType');
    Route::post('/upload-unit', 'UnitsController@postUploadUnit');
    Route::post('/delete', 'UnitsController@postDelete');
    Route::get('/units-variations/{slug}', 'UnitsController@getUnitVariations');
    Route::post('/units-variations/{slug}', 'UnitsController@postUnitVariations');
    Route::get('/delete-variation/{slug}', 'UnitsController@postDeleteVariation');
//    Route::get('/settings/{slug}', 'UnitsController@getSettings');
    Route::get('/live-settings/{slug}', 'UnitsController@unitPreview');
//    Route::get('/settings-iframe/{slug}/{settings?}', 'UnitsController@unitPerviewIframe');
//    Route::post('/settings/{id}/{save?}', 'UnitsController@postSettings');
    Route::any('/make-default/{slug}', 'UnitsController@getMakeDefault');
    Route::any('/make-default-variation/{slug}', 'UnitsController@getDefaultVariation');
});

Route::group(['prefix' => 'gears'], function () {
    Route::get('/', 'GearsController@getIndex');
    Route::group(['prefix' => 'page-sections'], function () {
        Route::get('/', 'PageSectionsController@getIndex');
        Route::get('/settings/{slug}', 'PageSectionsController@getSettings');
        Route::get('/variations/{slug}', 'PageSectionsController@getVariations');
        Route::post('/settings/{slug}/{save?}', 'PageSectionsController@postSettings');
        Route::post('/console', 'PageSectionsController@getConsole');
        Route::post('/make-active', 'PageSectionsController@postMakeActive');
        Route::post('/upload', 'PageSectionsController@postUpload');
        Route::get('/delete-variation/{slug}', 'PageSectionsController@postDeleteVariation');
        Route::post('/delete', 'PageSectionsController@postDelete');
    });
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

