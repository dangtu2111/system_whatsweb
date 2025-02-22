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

Auth::routes(['verify' => true]);

Route::group(['prefix' => config('whatsweb.backend'), 'middleware' => ['auth', 'usertype:admin']], function() {
	Route::get('/', 'HomeController@index')->name('backend');

	Route::get('users/export', 'UserController@export')->name('users.export');
	Route::resource('users', 'UserController');

	Route::get('links/export', 'LinkController@index')->name('links.export');
	Route::resource('links', 'LinkController')->except(['show']);
	Route::post('links/show', 'LinkController@show')->name('links.show');

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
	$statistic = new App\Facades\Statistic;
	Route::post('total', function() use($statistic) {
		return $statistic->totalLink();
	})->name('stats.totalLink');

	Route::post('today-visit', function() use($statistic) {
		return $statistic->todayVisit();
	})->name('stats.todayVisit');

	Route::post('yesterday-visit', function() use($statistic) {
		return $statistic->yesterdayVisit();
	})->name('stats.yesterdayVisit');

	Route::post('seven-days-visit', function() use($statistic) {
		return $statistic->sevenDaysVisit();
	})->name('stats.sevenDaysVisit');

	Route::post('chart-7-days', function() use($statistic) {
		return response([
			'data' => $statistic->chart7days()
		], 200);
	})->name('stats.chart7days');

	Route::post('chart', function() use($statistic) {
		return response([
			'data' => $statistic->chart()
		], 200);
	})->name('stats.chart');
});