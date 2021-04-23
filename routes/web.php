<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

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
    return view('contact');
});
Route::get('/addcontact', function () {
    return view('/addcontact');
});

Route::get('/', [ContactController::class,'index']);

Route::post('/addcontact',[ContactController::class,'store']);

Route::get('/search', [ContactController::class,'search']);
Route::get('/delete/{id}', [ContactController::class,'delete']);
Route::get('/update/{id}', [ContactController::class,'edit']);
Route::post('/update/{id}', [ContactController::class,'update']);
