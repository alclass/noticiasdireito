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

use App\Http\Controllers\NoticiasController;

Route::get('/', [
  'as' => 'entranceroute',
  'uses' => 'NoticiasController@mount_newslisting_for_entrance',
]);

Route::get('/contato', [
  'as' => 'contatoroute',
  'uses' => function() {
    return view('contato');
  },
]);

Route::get('/sobre', [
  'as' => 'sobreroute',
  'uses' => function() {
    return view('sobre');
  },
]);

Route::get('/Sitemap.txt', [
  'as' => 'txtsitemaproute',
  'uses' => 'SitemapController@gen_dyn_download_txt_sitemap',
]);

Route::get('/{year}', [
  'as' => 'newsperyearroute',
  'uses' => 'NoticiasController@list_news_for_year',
]);

Route::get('/{year}/{month}', [
  'as' => 'newspermonthroute',
  'uses' => 'NoticiasController@list_news_for_month',
]);

Route::get('/{year}/{month}/{day}/{underlined_newstitle}', [
  'as' => 'newsobjectroute',
  'uses' => 'NoticiasController@show_newspage',
]);

Route::feeds();
