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




Auth::routes();


Route::get('clearance-tracker','ResignController@trackClearance');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('Dashboard');
    Route::get('/home', 'HomeController@index')->name('Dashboard');
    

    //Resigned Employee
    Route::get('/resigned-employees','ResignController@index')->name('Resigned');
    Route::get('/upload','ResignController@upload')->name('Upload Resigned');
    Route::post('/upload','ResignController@store')->name('Upload Resigned');
    Route::get('setup-clearance/{id}','ResignController@setupClearance')->name('Setup Clearance');
    Route::post('setup-clearance/{id}','ResignController@setupClearancePost')->name('Setup Clearance');


    Route::get('/signatories','SignatoryController@index')->name('Signatories');
    Route::post('/signatories','SignatoryController@store')->name('Signatories');


    Route::post('add-checklist/{id}','ChecklistController@store');
    Route::get('/remove-signatories/{id}','ChecklistController@remove');

    Route::post('add-signatory/{id}','SignatoryController@addSignatory');

    Route::get('ongoing-clearance','ExitClearanceController@index')->name('Ongoing Clearance');
    Route::get('view-clearance/{id}','ExitClearanceController@view')->name('View Clearance');
    Route::get('for-clearance','ExitClearanceController@forClearance')->name('For Clearance');
    Route::get('view-as-signatory/{id}','ExitClearanceController@viewAsSignatory')->name('Signatory');
    Route::get('view-comments/{id}','ExitClearanceController@viewComments')->name('Comments');
    Route::get('my-clearance','ExitClearanceController@viewMyClearance')->name('Clearances');
    Route::post('new-comment/{id}','ExitClearanceController@submitComment');

    Route::post('change-status-checklist/{id}','ExitClearanceController@changestatus')->name('Change Status');
    Route::post('mark-as-cleared/{id}','ExitClearanceController@cleared')->name('Change Status');


    Route::get('cleared','ExitClearanceController@clear_index')->name('Cleared');
    Route::get('for-computation','ExitClearanceController@forComputation')->name('For Computation');
    Route::get('for-release','ExitClearanceController@forRelease')->name('For Release');
    Route::get('released','ExitClearanceController@released')->name('Released');
    Route::post('update-exit-status/{id}','ExitClearanceController@updateExitStatus')->name('Update Exit Status');
    Route::get('clearance-status-document/{id}','ExitClearanceController@downloadStatusDocument')->name('Clearance Status Document');

    Route::post('update-signatories/{id}', 'SignatoryController@update');

    Route::get('generate-clearance-form/{id}','ExitClearanceController@generateClearanceForm');

});

