<?php

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

function permissao($id){
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
    return view('welcome');
});

Route::get('sistema', function () {
    $permitido = permissao(Auth::user()->id);
    return view('sistema', compact('permitido'));
})->middleware('auth');


