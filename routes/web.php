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

Route::get('/', 'CompaniesController@showAll');
Route::get('/company/{id}', 'CompaniesController@showCompany');
Route::get('/tool/{slug}', 'ToolsController@showTool');

Route::get('/scraper/stackoverflow', 'ScraperController@scrapeStackOverflow');

Route::get('/scraper/weworkremotely', 'ScraperController@scrapeWeWorkRemotely');

Route::get('/scraper/remoteok', 'ScraperController@scrapeRemoteOK');

Route::get('/scraper/twitter-url', 'ScraperController@scrapeTwitterUrl');
Route::get('/scraper/twitter-profile', 'ScraperController@scrapeTwitterProfile');

Route::get('/scraper/glassdoor-ratings', 'ScraperController@scrapeRatingsOnGlassDoor');
Route::get('/scraper/glassdoor-company', 'ScraperController@scrapeCompanyOnGlassDoor');

Route::get('/scraper/stackshare-tools', 'ScraperController@scrapeToolsOnStackShare');
Route::get('/scraper/stackshare-tool', 'ScraperController@scrapeToolOnStackShare');
Route::get('/scraper/stackshare-company', 'ScraperController@scrapeCompanyOnStackShare');


Route::get('/scraper/ft', 'ScraperController@findTools');

Route::get('/search-tools/{keyword}', 'SearchController@searchTools');



