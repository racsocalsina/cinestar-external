<?php

return [

    'register_type_record' => '1',
    'register_type_aim' => '2',
    'register_initial_state' => 1,
    'incomplete_state_id' => 1,
    'sent_to_validate_state_id' => 2,
    'aprove_state_id' => 3,
    'reject_state_id' => 4,
    'support_email' => 'support@example.pe',
    'role_support_id' => 1,

    /*
    |--------------------------------------------------------------------------
    | Variables Globales de Tipos de Documentos
    |--------------------------------------------------------------------------
    */
    'types_documents'         => ['01', '04', '06', '07', '11', '00'],
    'type_document_dni'       => '01',
    'type_document_cde'       => '04',
    'type_document_rudc'      => '06',
    'type_document_pasaporte' => '07',
    'type_document_pdni'      => '11',
    'type_document_otros'     => '00',


    /*
    |--------------------------------------------------------------------------
    | Variables Globales de Rutas de imagenes
    |--------------------------------------------------------------------------
    */
    'path_images' => env('OBS_BASE_URL'),
    'path_movies' => env('APP_URL').'/storage/images/movies/',
    'path_headquarters' => env('APP_URL').'/storage/images/headquarters/',
    'path_banners' => env('APP_URL').'/storage/images/banners/',
    'path_customers' => env('APP_URL').'/storage/images/customers/',


    /*
    |--------------------------------------------------------------------------
    | Variables Globales exclusivas para el api
    |--------------------------------------------------------------------------
    */
    'api' => [
        'per_page' => 50,
    ],

];
