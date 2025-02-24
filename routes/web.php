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
use  App\Http\Controllers\StatisticController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\RedirectController;
Auth::routes(['verify' => true]);

Route::group(['prefix' => config('whatsweb.backend'), 'middleware' => ['auth', 'usertype:admin']], function() {
	Route::get('/', 'HomeController@index')->name('backend');

	Route::get('users/export', 'UserController@export')->name('users.export');
	Route::resource('users', 'UserController');

	Route::get('links/export', 'LinkController@index')->name('links.export');
	Route::resource('links', 'LinkController')->except(['show']);
	Route::post('links/show', 'LinkController@show')->name('links.show');
	
	Route::get('destination_url/export', 'DestinationURLController@index')->name('destination_url.export');
	Route::resource('destination_url', 'DestinationURLController')->except(['show']);
	Route::post('destination_url/show', 'DestinationURLController@show')->name('destination_url.show');

	Route::get('reports', 'ReportController@index')->name('reports.index');
	Route::resource('settings', 'SettingController');
	Route::resource('pages', 'PageController');
	Route::resource('posts', 'PostController');
});

Route::group([
	'prefix' => config('whatsweb.dashboard'), 
	'middleware' => ['auth', 'usertype:member'],
], function() {
	// Dashboard Index
	Route::get('/', 'Dashboard\DashboardController@index')->name('dashboard');

	// Links
	Route::get('/links', 'LinkController@index')->name('dashboard.links.index');
	Route::get('/links/create', 'LinkController@create')->name('dashboard.links.create');
	Route::get('/links/{id}/edit', 'LinkController@edit')->name('dashboard.links.edit');
	Route::post('/links/create', 'LinkController@store')->name('dashboard.links.store');
	Route::post('/links/show', 'LinkController@show')->name('dashboard.links.show');
	Route::put('/links/{id}', 'LinkController@update')->name('dashboard.links.update');
	Route::patch('/links/{id}', 'LinkController@update')->name('dashboard.links.update');
	Route::delete('/links/{id}/destroy', 'LinkController@destroy')->name('dashboard.links.destroy');

	// //Destination URL
	Route::get('/destination_url', 'DestinationURLController@index')->name('dashboard.destinationURL.index');
	Route::get('/destination_url/create', 'DestinationURLController@create')->name('dashboard.destinationURL.create');
	Route::get('/destinationURL/{id}/edit', 'DestinationURLController@edit')->name('dashboard.destinationURL.edit');
	Route::post('/destination_url/create', 'DestinationURLController@store')->name('dashboard.destinationURL.store');
	Route::post('/destination_url/show', 'DestinationURLController@show')->name('dashboard.destinationURL.show');
	Route::put('/destination_url/{id}', 'DestinationURLController@update')->name('dashboard.destinationURL.update');
	Route::patch('/destination_url/{id}', 'DestinationURLController@update')->name('dashboard.destinationURL.update');
	Route::delete('/destination_url/{id}/destroy', 'DestinationURLController@destroy')->name('dashboard.destinationURL.destroy');
	// Report
	Route::get('reports', 'ReportController@index')->name('dashboard.reports.index');

	// Settings
	Route::get('/settings', 'Dashboard\SettingController@index')->name('dashboard.settings.index');
	Route::put('/settings', 'Dashboard\SettingController@update')->name('dashboard.settings.update');
	Route::patch('/settings', 'Dashboard\SettingController@update')->name('dashboard.settings.update');
});

Route::get('', 'Frontend\HomeController@index')->name('home');
Route::get('auth/{provider}', 'Auth\LoginController@redirectToProvider')->name('loginwith');
Route::get('auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('loginwith_callback');
Route::get('{slug}', 'LinkController@slug')->name('slug');
Route::get('qrcode/{id}/{action?}', 'LinkController@qrcode')->name('qrcode');
Route::get('page/{slug}', 'PageController@show')->name('page.show');
Route::post('/links/create', 'LinkController@store')->name('links.store');

Route::group(['prefix' => '{prefix?}/stats', 'middleware' => 'auth'], function() {
    Route::post('total', [StatisticController::class, 'totalLink'])->name('stats.totalLink');
    Route::post('today-visit', [StatisticController::class, 'todayVisit'])->name('stats.todayVisit');
    Route::post('yesterday-visit', [StatisticController::class, 'yesterdayVisit'])->name('stats.yesterdayVisit');
    Route::post('seven-days-visit', [StatisticController::class, 'sevenDaysVisit'])->name('stats.sevenDaysVisit');
    Route::post('chart-7-days', [StatisticController::class, 'chart7days'])->name('stats.chart7days');
    Route::post('chart', [StatisticController::class, 'chart'])->name('stats.chart');
	// Thống kê số người đang truy cập
    Route::post('active-visitors', [StatisticController::class, 'getActiveVisitors'])->name('stats.activeVisitors');
});


