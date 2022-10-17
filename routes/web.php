<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

function permissao($id)
{
    switch ($id) {
        case '1':
            return true;
        case '2':
            return true;
        default:
            return false;
    }
}

Auth::routes();

Route::get('/', function () {
    $permitido = isset(Auth::user()->id) ? permissao(Auth::user()->id) : false;
    return view('welcome', compact('permitido'));
});

Route::resource('sistema', 'App\Http\Controllers\SistemaController')->middleware('auth');

Route::get('cadastro', function () {
    return 'cadastro';
})->name('cadastro');

Route::get('search', 'App\Http\Controllers\SistemaController@search')->middleware('auth')->name('search');
