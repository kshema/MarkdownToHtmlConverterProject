<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarkdownToHtmlController;

Route::get('/mhc', 'App\Http\Controllers\MarkdownToHtmlController@index');
Route::post('/convert', 'App\Http\Controllers\MarkdownToHtmlController@convert');
