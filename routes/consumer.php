<?php

use Illuminate\Support\Facades\Route;


Route::group([], function (){
    Route::get('headquarters', 'Headquarter\HeadquarterController@index')->middleware(['check-static-token']);
});




