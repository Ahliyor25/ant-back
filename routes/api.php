<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
*/
Route::post('/send-code', [\App\Http\Controllers\OtpController::class, 'phoneVerify']);
Route::post('/send-code-email', [\App\Http\Controllers\OtpController::class, 'emailVerify']);

    Route::group(['prefix' => 'auth', 'middleware' => 'guest'], function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/login-by-password', [\App\Http\Controllers\Auth\LoginController::class, 'loginByPassword']);
    Route::post('/admin-login', [\App\Http\Controllers\Auth\LoginController::class, 'adminLogin']);
    Route::post('/register', [RegisterController::class, 'register']);

});

Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\UserController::class, 'me']);

    // get admin profile
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/admin-profile', [\App\Http\Controllers\UserController::class, 'adminMe']);
    });

    Route::put('/profile', [\App\Http\Controllers\UserController::class, 'update']);

    Route::post('/change-password', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'changePassword']);
    Route::get('/logout', [LoginController::class, 'logout']);
});



Route::post('/forgot-password/phone', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPasswordWithPhone']);
Route::post('/forgot-password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPasswordWithEmail']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admins', [\App\Http\Controllers\UserController::class, 'admins']);
    Route::get('/roles', [\App\Http\Controllers\UserController::class, 'roles']);
});

Route::middleware(['auth:sanctum', 'role:admin,manager'])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\UserController::class, 'adminMe']);
    // users
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index']);
    Route::post('/users', [\App\Http\Controllers\UserController::class, 'store']);
    Route::put('/users/{user}', [\App\Http\Controllers\UserController::class, 'updateUser']);
    Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy']);

    //category
    Route::controller(\App\Http\Controllers\CategoryController::class)->prefix('categories')->group(function () {
        Route::post('/', 'store');
        Route::put('/{category}', 'update');
        Route::delete('/{category}', 'destroy');
    });


    // order call
    Route::controller(\App\Http\Controllers\OrderCallController::class)->prefix('order-call')->group(function () {
        Route::put('/{order_call}', 'update');
        Route::delete('/{order_call}', 'destroy');
        Route::get('/', 'index');
        Route::get('/{order_call}', 'show');
    });

    // payment methods
    Route::controller(\App\Http\Controllers\PaymentMethodController::class)->prefix('payment-methods')->group(function () {
        Route::post('/', 'store');
        Route::put('/{paymentMethod}', 'update');
        Route::delete('/{paymentMethod}', 'destroy');
    });
    // shipping methods
    Route::controller(\App\Http\Controllers\ShippingTypeController::class)->prefix('shipping-types')->group(function () {
        Route::post('/', 'store');
        Route::put('/{shippingType}', 'update');
        Route::delete('/{shippingType}', 'destroy');
    });

    // ShippingLocation
    Route::controller(\App\Http\Controllers\ShippingLocationController::class)
        ->prefix('/shipping-locations')
        ->group(function () {
            Route::post('/', 'store');
            Route::put('/{shippingLocation}', 'update');
            Route::delete('/{shippingLocation}', 'destroy');
        });

    // Banner
    Route::controller(\App\Http\Controllers\BannerController::class)->prefix('banners')->group(function () {
        Route::post('/', 'store');
        Route::put('/{banner}', 'update');
        Route::delete('/{banner}', 'destroy');
    });


    //VacancyCategory
    Route::controller(\App\Http\Controllers\VacancyCategoryController::class)
        ->prefix('/vacancy-categories')
        ->group(function () {
            Route::post('/', 'store');
            Route::put('/{vacancyCategory}', 'update');
            Route::delete('/{vacancyCategory}', 'destroy');
        });

    //Vacancy
    Route::controller(\App\Http\Controllers\VacancyController::class)
        ->prefix('/vacancies')
        ->group(function () {
            Route::post('/', 'store');
            Route::put('/{vacancy}', 'update');
            Route::delete('/{vacancy}', 'destroy');
        });


    // Project
    Route::controller(\App\Http\Controllers\ProjectController::class)
        ->prefix('projects')
        ->group(function () {
            Route::post('/', 'store');
            Route::put('/{project}', 'update');
            Route::delete('/{project}', 'destroy');
        });

    // ProjectImage
    Route::controller(\App\Http\Controllers\ProjectImageController::class)
        ->prefix('project-images')
        ->group(function () {
            Route::post('/', 'store');
            Route::put('/{projectImage}', 'update');
            Route::delete('/{projectImage}', 'destroy');
        });
});

// no guard

// OrderCall
Route::controller(\App\Http\Controllers\OrderCallController::class)->prefix('/order-call')->group(function () {
    Route::post('/', 'store');
});


//Banner
Route::controller(\App\Http\Controllers\BannerController::class)->prefix('/banners')->group(function () {
    Route::get('/', 'index');
    Route::get('/{type}', 'show');
});

// VacancyCategory
Route::get('/vacancy-categories', [\App\Http\Controllers\VacancyCategoryController::class, 'index']);
Route::get('/vacancy-categories/{vacancyCategory}', [\App\Http\Controllers\VacancyCategoryController::class, 'show']);

// Vacancy
Route::get('/vacancies', [\App\Http\Controllers\VacancyController::class, 'index']);
Route::post('/vacancies/lead', [\App\Http\Controllers\VacancyRequestController::class, 'sendMessage']);
Route::get('/vacancies/{vacancy:slug}', [\App\Http\Controllers\VacancyController::class, 'show']);


// Project
Route::get('/projects', [\App\Http\Controllers\ProjectController::class, 'index']);
Route::get('/projects/{project:slug}', [\App\Http\Controllers\ProjectController::class, 'show']);

// ProjectImage
Route::get('/project-images/{project:slug}', [\App\Http\Controllers\ProjectImageController::class, 'index']);

// Language
Route::get('/languages', [\App\Http\Controllers\LanguageController::class, 'index']);


