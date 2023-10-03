<?php

use Illuminate\Support\Facades\Route;


Route::namespace('App\Http\Controllers')->prefix('v1')->middleware("checkAppVersionHeader")->group(function () {

    # ---------------------------------------------------------
    # Public routes
    # ---------------------------------------------------------

    // Auth
    Route::post('auth/login', 'Auth\LoginApiController@apiLogin')->name('login');
    Route::post('auth/signup', 'Auth\LoginApiController@store');

    Route::post('auth/recovery/send-email', 'Auth\ForgotPasswordApiController@sendLinkEmail');
    Route::post('auth/recovery/send-sms', 'Auth\ForgotPasswordApiController@sendSms');
    Route::post('auth/recovery/change-password', 'Auth\ForgotPasswordApiController@changePassword');
    Route::post('auth/recovery/reset-password', 'Auth\ForgotPasswordApiController@resetPassword');

    // Headquarters
    Route::get('headquarters', 'API\HeadquartersController@index');
    Route::get('headquarters/{id_headquarter}', 'API\HeadquartersController@detailHeadquarter');
    Route::get('headquarter-by-url', 'API\HeadquartersController@getByUrl');

	//test
    Route::get('/test','API\HeadquartersController@test');

    // Movies
//    Route::get('newmovies/', 'API\MoviesController@newlistMovies');
    Route::get('movies/', 'API\MoviesController@newlistMovies');
    Route::get('movies/{id_movie}', 'API\MoviesController@detailMovie');

    // Movie-times
    Route::get('movies-times/', 'API\MovieTimesController@listMovieTimes');
    Route::get('movie-times/{id}/tariffs', 'API\MovieTimesController@listTariffs');

    // Claims
    Route::get('claims/parameters', 'API\ClaimController@parameters');
    Route::resource('claims', 'API\ClaimController');
    Route::get('claims/{claim}/download', 'API\ClaimController@download');

    // Job-Applications
    Route::post('job-applications', 'API\JobApplicationController@store');

    // Contacts
    Route::post('contacts', 'API\ContactController@store');

    // Formats
    Route::get('movie-formats', 'API\Share\MovieFormatsController@index');

    // Cities
    Route::apiResource('cities', 'API\Share\CitiesController');

    // Masters
    Route::get('masters/ubigeo', 'API\Share\UbigeoController@index');
    Route::get('masters/type_documents', 'API\Share\TypeDocumentsController@index');
    Route::get('masters/movie_genres', 'API\Share\MovieGenresController@index');
    Route::get('masters/billboard_dates', 'API\Share\SettingsController@billboardDates');
    Route::get('masters/billboard_dates/next_releases', 'API\Share\SettingsController@billboardDatesNextReleases');

    // Partners
    Route::get('partners/promotions', 'API\Partners\PartnerController@getPromotions');
    Route::get('partners/awards', 'API\Partners\PartnerController@getAwards');

    // Products
    Route::group(['namespace' => 'API\Products'], function () {
        Route::get('product-types', 'ProductTypeController@indexByProduct');
        Route::get('combo-types', 'ProductTypeController@indexByCombo');
        Route::get('promotions-types', 'ProductTypeController@indexByPromotion');

        Route::get('products', 'ProductController@indexByProduct');
        Route::get('combos', 'ProductController@indexByCombo');
        Route::get('choco-promotions', 'ProductController@indexByPromotion');
    });

    // Purchases
    Route::group(['namespace' => 'API\Purchases'], function () {

        Route::get('purchases/{id}/graph', 'PurchaseController@getPlannerGraph');
        Route::post('purchases', 'PurchaseController@store');
        Route::patch('purchases/{id}', 'PurchaseController@update');
        Route::post('purchases/{id}/seat', 'PurchaseController@updateSeat');
        Route::delete('purchases/{id}', 'PurchaseController@destroy');

        Route::post('sweets/purchases', 'SweetPurchaseController@store');
        Route::patch('sweets/purchases/{purchase}', 'SweetPurchaseController@update');

        Route::post('purchases/pay', 'PurchasePaymentController');
    });

    // Content Managements
    Route::group(['namespace'=>'API\ContentManagements'],   function () {
        Route::get('contents/partner', 'ContentManagementController@showPartnerData');
        Route::get('contents/corporate', 'ContentManagementController@showCorporateData');
        Route::get('contents/about-us', 'ContentManagementController@showAboutData');
        Route::get('contents/terms', 'ContentManagementController@showTermData');
        Route::get('contents/popup-banner', 'ContentManagementController@showPopupBannerData');
    });

    // Others
    Route::get('check-points','API\Points\PointController@checkPoints');
    Route::get('promotions-web', 'API\Promotion\PromotionController@index');
    Route::post('consult_code/{code}', 'API\Promotion\PromotionController@consultCode');
    Route::get('get-banners', 'API\HomeController@getBanners');

    # ---------------------------------------------------------
    # Private routes
    # ---------------------------------------------------------
    Route::group(['middleware' => 'auth:api'], function () {

        // Auth
        Route::post('auth/logout', 'Auth\LoginApiController@apilogout');
        Route::get('auth/get-profile', 'API\UsersController@getProfile');
        Route::post('auth/edit-profile', 'API\UsersController@editProfile');
        Route::post('auth/edit-image-profile', 'API\UsersController@editImageProfile');

        // Sweets Favorites
        Route::get('sweets/favorites', 'API\Sweets\SweetController@index');
        Route::post('sweets/toggle-favorite', 'API\Sweets\SweetController@toggleFavorite');

        // Cards
        Route::get('cards', 'API\Cards\CardController@index');
        Route::post('cards', 'API\Cards\CardController@store');
        Route::delete('cards/{token}', 'API\Cards\CardController@destroy');

        // Headquarters
        Route::get('users/headquarters', 'API\HeadquartersController@listAll');
        Route::post('headquarters/{id}/favorite', 'API\HeadquartersController@updateFavorite');



        // Awards
        Route::get('ticket_awards', 'API\Promotion\TicketAwardController@index');
        Route::get('choco_awards', 'API\Promotion\ChocoAwardController@index');

        // Purchases
        Route::get('purchases', 'API\Purchases\PurchaseController@index');
    });
});


Route::namespace('App\Http\Controllers')->prefix('v2')->middleware("checkAppVersionHeader")->group(function () {
    // Purchases
    Route::group(['namespace' => 'API\Purchases'], function () {
        Route::post('purchases/pay', 'PurchasePaymentController')->middleware('is_request_encrypted');
    });
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('cards', 'API\Cards\CardController@store')->middleware('is_request_encrypted');
    });
    Route::get('movies', 'API\MoviesController@listMoviesAndMovieTimes');
    Route::get('movies-times/dates', 'API\MovieTimesController@listMovieTimesDates');
});
