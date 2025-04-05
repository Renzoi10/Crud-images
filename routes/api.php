<?php

use App\Http\Controllers\ImageCrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::controller(ImageCrudController::class)-> group(function () {
    Route::get('/images', 'getArticles');
    Route::post('/images', 'addArticle');
    Route::get('/images/{id}', 'getArticlesById');
    Route::post('/images/{id}', 'updateArticle');
    Route::delete('/images/{id}', 'deleteArticle');
});
