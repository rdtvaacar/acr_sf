<?php

Route::group(['middleware' => ['web']], function () {
    Route::group([
        'namespace' => 'Acr\sf\Controllers',
        'prefix'    => 'acr/sf'
    ], function () {
        Route::get('/', 'BlogController@blog_galery');
        Route::get('/oku', 'BlogController@blog_oku');
        Route::group(['middleware' => ['auth']], function () {
            Route::get('/kurum', 'SfController@kurum');
            Route::get('/kurum/ekle', 'SfController@kurum_ekle');
            Route::post('/kurum/duyuru/kaydet', 'SfController@duyuru_kaydet');
            Route::post('/kurum/duyuru/delete', 'SfController@duyuru_delete');
            Route::post('/kurum/duyuru/incele', 'SfController@duyuru_incele');
            Route::post('/kurum/duyuru/oku', 'SfController@duyuru_oku');
            Route::post('/kurum/duyuru/statistic', 'SfController@duyuru_statistic');
            Route::get('/kurum/wh', 'SfController@wh');
            Route::group(['middleware' => ['admin']], function () {
            });
        });
    });
});