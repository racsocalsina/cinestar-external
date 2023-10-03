<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth', 'middleware' => []], function () {
    Route::post('login', 'Auth\LoginController@login');
});

Route::group(['middleware' => ['auth:admin']],   function () {

    Route::get('ping', function () { return true; });

    // Movie Genders
    Route::group(['namespace'=>'Banners'],   function () {
        Route::get('banners', 'BannerController@index');
        Route::post('banners', 'BannerController@store');
        Route::post('banners/update/{banner}', 'BannerController@update');
        Route::delete('banners/{banner}', 'BannerController@destroy');
    });

    // Roles
    Route::get('roles/parameters', 'Roles\RoleController@parameters');
    Route::apiResource('roles', 'Roles\RoleController');

    // Users
    Route::group(['namespace'=>'Admins'],   function () {
        Route::get('users/parameters', 'AdminController@parameters');
        Route::apiResource('users', 'AdminController');
        Route::patch('users/{id}/toggle-status', 'AdminController@toggleStatus');
    });
    Route::group(['namespace'=>'Customers'],   function () {
        Route::get('customers', 'CustomerController@index');
        Route::get('customers/ranking', 'CustomerController@ranking');
        Route::get('customers/export', 'CustomerController@export');
    });
    //Reports
    Route::group(['namespace'=>'Purchases'],   function () {
        Route::get('tickets/stats', 'PurchaseController@getTotals');
        Route::get('tickets/parameters', 'PurchaseController@getParameters');
    });

    Route::get('reports/exhibitor-monthly', 'Reports\ExhibitorMonthlyController@index');
    Route::get('reports/qr-status', 'Reports\QrStatusController@index');

    // Movies
    Route::group(['namespace'=>'Movies'],   function () {
        Route::post('movies/sync', 'MovieController@sync');
        Route::put('movies/{movie}', 'MovieController@update');
        Route::get('movies', 'MovieController@index');
        Route::get('movies/parameters', 'MovieController@parameters');
        Route::patch('movies/{movie}/toggle-status', 'MovieController@toggleStatus');
        Route::get('movies/{movie}/headquarters', 'MovieHeadquarterController@index');
    });

    // Ticket Promos
    Route::group(['namespace'=>'TicketPromotions'],   function () {
        Route::get('ticket-promotions/parameters', 'TicketPromotionController@parameters');
        Route::get('ticket-promotions', 'TicketPromotionController@index');
        Route::post('ticket-promotions/sync', 'TicketPromotionController@sync');
        Route::put('ticket-promotions/{ticketPromotion}', 'TicketPromotionController@update');
    });

    // Choco Promos
    Route::group(['namespace'=>'ChocoPromos'],   function () {
        Route::get('choco-promotions', 'ChocoPromosController@index');
        Route::post('choco-promotions/sync', 'ChocoPromosController@sync');
        Route::post('choco-promotions/products/sync', 'ChocoPromosController@syncProducts');
        Route::put('choco-promotions/{chocoPromotion}', 'ChocoPromosController@update');
    });

    // Ticket Awards
    Route::group(['namespace'=>'TicketAwards'],   function () {
        Route::get('ticket-awards', 'TicketAwardController@index');
        Route::post('ticket-awards/sync', 'TicketAwardController@sync');
        Route::put('ticket-awards/{ticketAward}', 'TicketAwardController@update');
    });

    // Choco Awards
    Route::group(['namespace'=>'ChocoAwards'],   function () {
        Route::get('choco-awards', 'ChocoAwardController@index');
        Route::post('choco-awards/sync', 'ChocoAwardController@sync');
        Route::put('choco-awards/{chocoAward}', 'ChocoAwardController@update');
    });

    //Movie Times
    Route::group(['namespace'=>'MovieTimes'],   function () {
        Route::patch('movie-times/{movieTime}/toggle-status', 'MovieTimeController@toggleStatus');
        Route::post('movie-times/update-graph', 'MovieTimeController@updateGraph');
        Route::put('movie-times/{movieTime}', 'MovieTimeController@update');
        Route::post('movie-times/sync', 'MovieTimeController@sync');
    });

    //Movie Time Prices
    Route::group(['namespace'=>'MovieTimeTariffs'],   function () {
        Route::post('movie-time-tariff/sync', 'MovieTimeTariffController@sync');
    });

    // Movie Genders
    Route::group(['namespace'=>'MovieGenders'],   function () {
        Route::apiResource('movie-genders', 'MovieGenderController');
        Route::patch('movie-genders/{id}/toggle-status', 'MovieGenderController@toggleStatus');
    });

    //Movie Formats
    Route::group(['namespace'=>'MovieFormats'],   function () {
        Route::apiResource('movie-formats', 'MovieFormatController');
        Route::patch('movie-formats/{id}/toggle-status', 'MovieFormatController@toggleStatus');
    });

    // Headquarters
    Route::group(['namespace'=>'Headquarters'],   function () {
        Route::get('headquarters/parameters', 'HeadquarterController@parameters');
        Route::apiResource('headquarters', 'HeadquarterController');
        Route::patch('headquarters/{id}/toggle-status', 'HeadquarterController@toggleStatus');
        Route::get('headquarters/{headquarter}/movie-times', 'HeadquarterMovieTimeController@index');
        Route::get('headquarters/{headquarter}/sync', 'HeadquarterSyncController@sync');

        // Headquarter Images
        Route::get('headquarter-images/{headquarterImage}/mark-as-main', 'HeadquarterImageController@markAsMain');
        Route::delete('headquarter-images/{headquarterImage}', 'HeadquarterImageController@destroy');
        Route::post('headquarters/{headquarter}/images', 'HeadquarterImageController@store');
    });

    // Rooms
    Route::group(['namespace'=>'Rooms'],   function () {
        Route::post('rooms/sync', 'RoomController@sync');
    });

    // Cities
    Route::group(['namespace'=>'Cities'],   function () {
        Route::get('cities/parameters', 'CityController@parameters');
        Route::get('cities', 'CityController@index');
        Route::post('cities', 'CityController@store');
        Route::put('cities/{city}', 'CityController@update');
        Route::delete('cities/{city}', 'CityController@destroy');
    });

    // Countries
    Route::group(['namespace'=>'Countries'],   function () {
        Route::resource('countries', 'CountryController');
    });

    // Product Price
    Route::group(['namespace'=>'ProductPrices'],   function () {
        Route::post('product-price/sync', 'ProductPriceController@sync');
    });

    // Products
    Route::group(['namespace'=>'Products'],   function () {
        Route::post('products/sync', 'ProductController@sync');
        Route::get('products/stats', 'ProductController@getTotals');
        Route::get('products/parameters', 'ProductController@getParameters');
        Route::post('products/{product}', 'ProductController@update');
        Route::put('products/{product}', 'ProductController@update');

        Route::get('products/{product}/headquarters', 'ProductController@getAvailableHeadquartersByProduct');
        Route::get('products', 'ProductController@indexByProduct');

        Route::get('combos/{product}/headquarters', 'ProductController@getAvailableHeadquartersByCombo');
        Route::get('combos', 'ProductController@indexByCombo');
    });
    
    // ProductTypes
    Route::group(['namespace'=>'ProductTypes'],   function () {
        Route::post('product-types/sync', 'ProductTypeController@sync');
    });

    // Settings
    Route::group(['namespace'=>'Settings'],   function () {
        Route::post('settings/sync', 'SettingController@sync');
        Route::apiResource('settings/system-configurations', 'SystemConfigurationController');
        Route::get('settings/erp-system-vars', 'SettingController@getErpSystemVars');
    });

    // Bins
    Route::group(['namespace'=>'Bins'],   function () {
        Route::post('bins/sync', 'BinController@sync');
    });

    // Masters
    Route::group(['namespace'=>'Masters', 'prefix' => 'masters'],   function () {
        Route::get('product-types', 'MasterProductTypeController@indexByProduct');
        Route::get('combo-types', 'MasterProductTypeController@indexByCombo');
    });

    // Purchases
    Route::group(['namespace'=>'Purchases'],   function () {
        Route::get('purchases', 'PurchaseController@index');
        Route::get('purchases/parameters', 'PurchaseController@parameters');
        Route::post('purchases/{purchase}/completed', 'PurchaseCompletedProcessController');
        Route::post('purchases/{purchase}/cancelled', 'PurchaseCancelledProcessController');
        Route::post('purchases-transaction', 'PurchaseController@purchasesTransactionPayu');
        Route::get('purchases-transaction', 'PurchaseController@indexTransaction');
        Route::get('purchases-transaction/parameters', 'PurchaseController@transactionParameters');
        Route::get('purchases-transaction/transactionsPerDay', 'PurchaseController@transactionsPerDay');
        Route::get('purchases-transaction/transactionsPerMonth', 'PurchaseController@transactionsPerMonth');
        Route::get('purchases-transaction/transactionsPerWeek', 'PurchaseController@transactionsPerWeek');
    });

    // Logs
    Route::group(['namespace'=>'Errors'],   function () {
        Route::get('logs/internal-errors', 'InternalErrorController@index');
        Route::get('logs/internal-errors/parameters', 'InternalErrorController@parameters');
    });

    // Content Managements
    Route::group(['namespace'=>'ContentManagements'],   function () {
        Route::get('content-managements/partner', 'PartnerController@show');
        Route::put('content-managements/partner', 'PartnerController@store');

        Route::get('content-managements/corporate', 'CorporateController@show');
        Route::put('content-managements/corporate', 'CorporateController@store');

        Route::get('content-managements/about', 'AboutController@show');
        Route::put('content-managements/about', 'AboutController@store');

        Route::get('content-managements/terms', 'TermController@show');
        Route::post('content-managements/terms', 'TermController@store');

        Route::get('content-managements/popup-banner', 'PopupBannerController@show');
        Route::put('content-managements/popup-banner', 'PopupBannerController@store');
    });

});

// Service for internal errors (job_triggers)
Route::group(['namespace'=>'Errors'],   function () {
    Route::post('errors/internal', 'InternalErrorController@store');
});
