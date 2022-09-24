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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bdcheck', function () {
    try{
        $dbh = new pdo( 'mysql:host=pesqueiroshalom.com.br:3306;dbname=u184072781_shalom',
                        'u184072781_shalom',
                        'H7#WtwDFw',
                        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        return 'bd ok';
    }
    catch(PDOException $ex){
        return 'falhou->'.$ex;
    }
});

Route::get('/sistema', function () {
    return view('sistema');
})->middleware('auth');

Auth::routes();

