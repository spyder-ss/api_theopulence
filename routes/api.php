<?php

use App\Http\Controllers\Api\BlogApiController;
use App\Http\Controllers\Api\EnquiryApiController;
use App\Http\Controllers\Api\GalleryApiController;
use App\Http\Controllers\Api\TestimonialApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'testimonials'], function () {
    Route::get('/', [TestimonialApiController::class, 'index']);
});

Route::group(['prefix' => 'blogs'], function () {
    Route::get('/', [BlogApiController::class, 'index']);
    Route::get('/{slug}', [BlogApiController::class, 'show']);
});

Route::group(['prefix' => 'gallery'], function () {
    Route::get('/categories', [GalleryApiController::class, 'categories']);
    Route::get('/images/{slug}', [GalleryApiController::class, 'images']);
});

Route::group(['prefix' => 'enquiries'], function () {
    Route::post('/', [EnquiryApiController::class, 'store']);
});
