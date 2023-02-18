<?php

use App\Http\Controllers\Api\V1\ApiAttendanceController;
use Illuminate\Support\Facades\Route;

// FATTURE IN CLOUD API2
Route::get('/fic/fetch/clients', function () {
    $token = 'a/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJyZWYiOiJWQ0ducU5yNEczaHRBUVpuV0cxR2JORDh4YWRBN3dtWiJ9.GWAzUo1p5RTYODQ5L8-JMKFl8rpjcThWcxCdIUOPPJM';
    $clientId = 'RtYnElTxIiUnTqneBKPCwmGuGd6DHPjJ';
    $companyId = '874844';

    $urlGetCompanies = 'https://api-v2.fattureincloud.it/user/companies';
    $url = "https://api-v2.fattureincloud.it/c/$companyId/entities/clients";

    $file = file_get_contents(resource_path().'/js/Helpers/Data/Clients.json');
    $data = json_decode($file, true)['data'];

    print_r($file);

//    return Http::acceptJson()
//        ->withHeaders([
//            'Authorization' => 'Bearer ' . $token
//        ])->get($url, ['fieldset' => 'detailed', 'per_page' => 500])->json();
});
