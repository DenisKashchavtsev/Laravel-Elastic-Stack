<?php

use App\Http\Controllers\ElasticsearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/index', [ElasticsearchController::class, 'index']);
Route::get('/search', [ElasticsearchController::class, 'search']);
Route::get('/elasticsearch', function () {
    return view('elasticsearch');
});
