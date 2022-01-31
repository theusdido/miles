<?php

use Illuminate\Support\Facades\Route;

// Miles Frameworks
require '/var/www/granuemporio.com.br/miles/core/autoload.php';

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

Route::get('/', function(){
    return view('layouts/kv-ee/home');
});

Route::get('/home', function(){
    return redirect('/');
});

Route::get('/conhecaaloja', function(){
    return view('layouts/kv-ee/galeriafotos');
});