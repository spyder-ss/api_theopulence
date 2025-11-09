<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\GalleryCategoryController;
use App\Http\Controllers\Admin\GalleryImageController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\AmenityController;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], '/', [AuthController::class, 'login'])->name('login');

Route::group(['prefix' => getAdminRouteName(), 'as' => getAdminRouteName() . '.', 'middleware' => ['auth', 'CheckPermission']], function () {
    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard'], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
    });

    Route::group(['prefix' => 'modules', 'as' => 'modules'], function () {
        Route::get('/', [ModuleController::class, 'index'])->name('index');
        Route::match(['get', 'post'], 'add', [ModuleController::class, 'add'])->name('add');
        Route::match(['get', 'post'], 'edit/{id}', [ModuleController::class, 'add'])->name('add');
        Route::match(['post'], 'delete/{id}', [ModuleController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'email_templates', 'as' => 'email_templates'], function () {
        Route::get('/', [EmailTemplateController::class, 'index'])->name('index');
        Route::match(['get', 'post'], 'add', [EmailTemplateController::class, 'add'])->name('add');
        Route::match(['get', 'post'], 'edit/{id}', [EmailTemplateController::class, 'add'])->name('add');
        Route::match(['post'], 'delete/{id}', [EmailTemplateController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'countries', 'as' => 'countries'], function () {
        Route::get('/', [CountryController::class, 'index'])->name('index');
        Route::match(['get', 'post'], 'add', [CountryController::class, 'add'])->name('add');
        Route::match(['get', 'post'], 'edit/{id}', [CountryController::class, 'add'])->name('add');
        Route::match(['post'], 'delete/{id}', [CountryController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'states', 'as' => 'states'], function () {
        Route::get('/', [StateController::class, 'index'])->name('index');
        Route::match(['get', 'post'], 'add', [StateController::class, 'add'])->name('add');
        Route::match(['get', 'post'], 'edit/{id}', [StateController::class, 'add'])->name('add');
        Route::match(['post'], 'delete/{id}', [StateController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'cities', 'as' => 'cities'], function () {
        Route::get('/', [CityController::class, 'index'])->name('index');
        Route::match(['get', 'post'], 'add', [CityController::class, 'add'])->name('add');
        Route::match(['get', 'post'], 'edit/{id}', [CityController::class, 'add'])->name('add');
        Route::match(['post'], 'delete/{id}', [CityController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'testimonials', 'as' => 'testimonials'], function () {
        Route::get('/', [TestimonialController::class, 'index'])->name('index');
        Route::match(['get', 'post'], 'add', [TestimonialController::class, 'add'])->name('add');
        Route::match(['get', 'post'], 'edit/{id}', [TestimonialController::class, 'add'])->name('add');
        Route::match(['post'], 'delete/{id}', [TestimonialController::class, 'delete'])->name('delete');
        Route::match(['post'], 'ajax_img_delete', [TestimonialController::class, 'ajax_img_delete'])->name('ajax_img_delete');
    });

    Route::group(['prefix' => 'cms_pages', 'as' => 'cms_pages'], function () {
        Route::get('/', [CmsPageController::class, 'index'])->name('index');
        Route::match(['get', 'post'], 'add', [CmsPageController::class, 'add'])->name('add');
        Route::match(['get', 'post'], 'edit/{id}', [CmsPageController::class, 'add'])->name('add');
        Route::match(['post'], 'delete/{id}', [CmsPageController::class, 'delete'])->name('delete');
        Route::match(['post'], 'ajax_img_delete', [CmsPageController::class, 'ajax_img_delete'])->name('delete');
    });

    Route::group(['prefix' => 'blogs', 'as' => 'blogs'], routes: function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::match(['get', 'post'], 'add', [BlogController::class, 'add'])->name('add');
        Route::match(['get', 'post'], 'edit/{id}', [BlogController::class, 'add'])->name('add');
        Route::match(['post'], 'delete/{id}', [BlogController::class, 'delete'])->name('delete');
        Route::match(['post'], 'ajax_img_delete', [BlogController::class, 'ajax_img_delete'])->name('delete');
    });

    Route::group(['prefix' => 'activity_logs', 'as' => 'activity_logs'], function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('details/{id}', [ActivityLogController::class, 'details'])->name('details');
    });

    Route::group(['prefix' => 'website_settings', 'as' => 'website_settings'], function () {
        Route::get('/', [WebsiteSettingController::class, 'index'])->name('index');
        Route::match(['get', 'post'], 'add', [WebsiteSettingController::class, 'add'])->name('add');
        Route::match(['get', 'post'], 'edit/{id}', [WebsiteSettingController::class, 'add'])->name('add');
        Route::match(['post'], 'delete/{id}', [WebsiteSettingController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'enquiries', 'as' => 'enquiries'], function () {
        Route::get('/', [EnquiryController::class, 'index'])->name('index');
        Route::match(['post'], 'delete/{id}', [EnquiryController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'users', 'as' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('customers', [UserController::class, 'customers'])->name('customers');
        Route::get('form', [UserController::class, 'form'])->name('add');
        Route::post('store', [UserController::class, 'add'])->name('store');
        Route::get('edit/{id}', [UserController::class, 'form'])->name('edit');
        Route::post('update/{id}', [UserController::class, 'update'])->name('update');
        Route::match(['post'], 'delete/{id}', [UserController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'roles', 'as' => 'roles'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::match(['get', 'post'], 'add', [RoleController::class, 'add'])->name('add');
        Route::match(['get', 'post'], 'edit/{id}', [RoleController::class, 'add'])->name('add');
        Route::match(['post'], 'delete/{id}', [RoleController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'gallery-categories', 'as' => 'gallery-categories.'], function () {
        Route::get('/', [GalleryCategoryController::class, 'index'])->name('index')->middleware('CheckPermission:gallery-categories,View');
        Route::match(['get', 'post'], 'add', [GalleryCategoryController::class, 'create'])->name('add')->middleware('CheckPermission:gallery-categories,Add');
        Route::match(['get', 'post'], 'edit/{gallery_category}', [GalleryCategoryController::class, 'edit'])->name('edit')->middleware('CheckPermission:gallery-categories,Edit');
        Route::match(['post'], 'delete/{gallery_category}', [GalleryCategoryController::class, 'destroy'])->name('delete')->middleware('CheckPermission:gallery-categories,Delete');
        Route::post('store', [GalleryCategoryController::class, 'store'])->name('store')->middleware('CheckPermission:gallery-categories,Add');
        Route::put('update/{gallery_category}', [GalleryCategoryController::class, 'update'])->name('update')->middleware('CheckPermission:gallery-categories,Edit');
    });

    Route::group(['prefix' => 'gallery-images', 'as' => 'gallery-images.'], function () {
        Route::get('/', [GalleryImageController::class, 'index'])->name('index')->middleware('CheckPermission:gallery-images,View');
        Route::match(['get', 'post'], 'add', [GalleryImageController::class, 'add'])->name('add')->middleware('CheckPermission:gallery-images,Add');
        Route::match(['get', 'post'], 'edit/{id}', [GalleryImageController::class, 'add'])->name('edit')->middleware('CheckPermission:gallery-images,Edit');
        Route::match(['post'], 'delete/{id}', [GalleryImageController::class, 'delete'])->name('delete')->middleware('CheckPermission:gallery-images,Delete');
        Route::match(['post'], 'ajax_img_delete', [GalleryImageController::class, 'ajax_img_delete'])->name('ajax_img_delete');
    });

    Route::group(['prefix' => 'amenities', 'as' => 'amenities'], routes: function () {
        Route::get('/', [AmenityController::class, 'index'])->name('index');
        Route::match(['get', 'post'], 'add', [AmenityController::class, 'add'])->name('add');
        Route::match(['get', 'post'], 'edit/{id}', [AmenityController::class, 'add'])->name('add');
        Route::match(['post'], 'delete/{id}', [AmenityController::class, 'delete'])->name('delete');
        Route::match(['post'], 'ajax_img_delete', [AmenityController::class, 'ajax_img_delete'])->name('delete');
    });

    Route::group(['prefix' => 'properties', 'as' => 'properties.'], function () {
        Route::get('/', [PropertyController::class, 'index'])->name('index');
        Route::match(['get', 'post'], 'add', [PropertyController::class, 'create'])->name('add');
        Route::match(['get', 'post'], 'edit/{property}', [PropertyController::class, 'edit'])->name('edit');
        Route::post('store', [PropertyController::class, 'store'])->name('store');
        Route::put('update/{property}', [PropertyController::class, 'update'])->name('update');
        Route::match(['post'], 'delete/{property}', [PropertyController::class, 'destroy'])->name('delete');
        Route::match(['post'], 'ajax_property_img_delete', [PropertyController::class, 'ajax_property_img_delete'])->name('ajax_property_img_delete');
        Route::post('generate-slug', [PropertyController::class, 'generateSlug'])->name('generate-slug');
    });

    Route::match(['get'], 'logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
});
