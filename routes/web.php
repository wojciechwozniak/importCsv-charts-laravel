<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('/import','ImportController@import')->name('import.import');
Route::post('/import_process', 'ImportController@processImport')->name('import.process');
Route::get('/import_success', 'ImportController@importSuccess')->name('import.success');
