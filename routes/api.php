<?php

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Resources\Company as CompanyResource;

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

Route::group(['prefix' => 'v1'], function () {
	Route::post('/post','CompaniesController@store');
	Route::get('/companies','CompaniesController@filter');
	// Route::post('/company/{slug}','CompaniesController@showCompanyBySlug');
	Route::post('/company/{slug}', function($slug){
		return new CompanyResource(
			Company::where('slug', $slug)->with(['tools.type', 'jobs.categories', 'benefits', 'hiring_regions','industries'])->first()
		);
	});

	Route::post('/alert', 'CompaniesController@alert');
	Route::post('/report', 'CompaniesController@report');

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
