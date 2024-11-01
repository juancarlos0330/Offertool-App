<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});


Auth::routes(['register' => true]);
// Admin

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    Route::get('users/pricerole/{id}', 'UsersController@pricerole')->name('users-pricerole');
    Route::post('user/priceroleupdate/{id}', 'UsersController@priceroleupdate')->name('users-priceroleupdate');


    // Scooters
    Route::delete('scooters/destroy', 'ScootersController@massDestroy')->name('scooters.massDestroy');
    Route::resource('scooters', 'ScootersController');
    Route::resource('pricings', 'PricingsController');
    Route::resource('pricemanage', 'PricemanageController');
    Route::resource('pricetype', 'PricetypeController');
    Route::resource('pricecompetitor', 'PricecompetitorController');
    Route::resource('language', 'LanguageController');
    Route::get('languages/{id}', 'LanguageController@langedit')->name('language.langedit');
    Route::post('languages/store/key/{id}', 'LanguageController@storeLanguageJson')->name('languages.store.key');
    Route::post('languages/delete/{id}', 'LanguageController@deleteLanguageJson')->name('languages.key.delete');
    Route::post('languages/edit/{id}', 'LanguageController@updateLanguageJson')->name('languages.key.edit');

    Route::get('languages/change/{lang?}', 'LanguageController@changeLanguage')->name('lang');

    Route::get('pricecompare/{id}', 'PricecompareController@index')->name('pricecompare-filter');
    Route::get('pricecompare/{pid}/create/{id}', 'PricecompareController@create')->name('pricecompare.create');
    Route::post('pricecompare/store', 'PricecompareController@store')->name('pricecompare.store');
    Route::get('pricecompare/{pid}/edit/{id}', 'PricecompareController@edit')->name('pricecompare.edit');
    Route::post('pricecompare/update', 'PricecompareController@update')->name('pricecompare.update');
    Route::get('pricecompare/{pid}/show/{id}', 'PricecompareController@show')->name('pricecompare.show');
    Route::get('pricecompare/destroy/{id}', 'PricecompareController@destroy')->name('pricecompare.destroy');
    Route::get('pricecompares/allcom', 'PricecompareController@allcompetitors')->name('pricecompares.allcom');
    Route::post('pricecompares/exportpdf', 'PricecompareController@generatePDF')->name('pricecompares.exportpdf');
    Route::get('pricecompares/viewforpdf', 'PricecompareController@viewForPDF')->name('pricecompares.viewforpdf');
    Route::post('pricecompares/onestore', 'PricecompareController@onestore')->name('pricecompares.onestore');

    Route::post('scooters-import', 'ScootersController@import')->name('scooters-import');
    Route::get('scooter-excel', 'ScootersController@excelimport')->name('scooters-excel');
    Route::get('scooter-deleteall', 'ScootersController@deleteAll')->name('scooters-delete-all');
    Route::post('scooter-deletebytype', 'ScootersController@deletebytype')->name('scooters-deletebytype');
    Route::get('scooter-pdf/{id}', 'ScootersController@generatePDF')->name('scooter-pdf');
    Route::post('scooters/{id}', 'ScootersController@uploadSIGN')->name('scooter-sign');
    
    Route::get('scooter/{id}', 'ScootersController@filterList')->name('scooters-filter');

    Route::get('history', 'HistoryController@index')->name('history');

    // Scooterstatuses
    Route::delete('scooter-statuses/destroy', 'ScooterStatusController@massDestroy')->name('scooter-statuses.massDestroy');
    Route::resource('scooter-statuses', 'ScooterStatusController');
});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
// Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
    }
});

