<?php

use Illuminate\Http\Request;
use GuzzleHttp\Client;
// use Artisan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



// API
Route::get('/attendance_reports', 'AttendanceReportController@attendance_reports');
Route::get('/users', 'UserController@users');
Route::get('/enyo_companies', 'CompanySettingController@enyo_companies');
Route::get('/branches', 'CompanySettingController@enyo_branches');
Route::get('/regions', 'RegionController@regions');
Route::get('/states', 'UserController@states');
Route::get('/roles', 'RoleController@roles');
Route::get('/shifts', 'RoleController@shifts');


// Company api
Route::get('/stations', 'CompanySettingController@apicompanies');

Route::get('/fetch-users', function () {
    // return phpinfo();
    // Artisan::queue('fetch:users');
    \Artisan::queue('fetch:users');
    return 0;
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/data', 'BiometricController@data');


Route::get('/iclock/cdata', 'BiometricController@checkDevice');
Route::post('/iclock/cdata', 'BiometricController@receiveRecords');
Route::get('/iclock/getrequest', 'BiometricController@getRequest');
Route::post('/iclock/devicecmd', 'BiometricController@deviceCMD');


Route::get('/iclock/getrequest', 'BiometricController@getrequest');
Route::get('/iclock/devicecmd', 'BiometricController@deviceCMD');
Route::get('/iclock/devicecmd', 'BiometricController@deviceCMD');

Route::get('/fetch-userss', function () {
    // //     // echo phpinfo();
    // //     // exit;
    // //     $exitCode = Artisan::call('fetch:users');
    // // return 'done';
    // //     //
    $client = new Client();
    $response = $client->post(
        'https://api.officelime.com:9099/v1/token',
        array(
            'headers' => ['client_id' => 'F58B3E9F-ADA7-4DB0-BE35642622868F78'],
            'form_params' => [
                'Email' => 'tobe@snapnet.com.ng',
                'Key' => 'MAdv4k3AN2WgR5ajxVzguOyTh/SStIuA6euQE0sVexryG0l9r8wJa'
            ], 'verify' => false,

        )
    );


    $auth_response = json_decode($response->getBody());
    return $token = $auth_response->token;
});

// Route::get('/fetch-users', function (){
//     $repsonse = '';
//     $cURLConnection = curl_init();
//     $headers = ['client_id' => 'F58B3E9F-ADA7-4DB0-BE35642622868F78', 'Content-type'=>'application/x-www-form-urlencoded'];
//     $postFields = [
//         'Email' => 'tobe@snapnet.com.ng',
//         'Key' => 'MAdv4k3AN2WgR5ajxVzguOyTh/SStIuA6euQE0sVexryG0l9r8wJa'
//     ];
//     $url = 'https://api.officelime.com:9099/v1/token';
//     curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $headers);
//     curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, http_build_query($postFields));
//     curl_setopt($cURLConnection, CURLOPT_URL, $url);
//     curl_setopt($cURLConnection, CURLOPT_POST, true);
//     curl_setopt($cURLConnection, CURLOPT_VERBOSE, true);
//     curl_setopt($cURLConnection, CURLOPT_FAILONERROR, true);
//     curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);
//     $response = curl_exec($cURLConnection);
//     if (curl_errno($cURLConnection)) {
//         $response = curl_error($cURLConnection);
//         echo $response;
//         exit;
//     }
//     curl_close($cURLConnection);

//     dd(json_decode($response));

// });
