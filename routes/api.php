<?php

use App\Http\Controllers\AbonentController;
use App\Http\Controllers\Api\SupportAbonentFaqController;
use App\Http\Controllers\Api\SupportPageTextController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\KpFileController;


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


Route::post('/abonent/mepassword', [AbonentController::class, 'mepassword']);
Route::post('/abonent/meauth', [AbonentController::class, 'meauth']);
Route::post('/abonent/cardadd', [AbonentController::class, 'cardadd']);
Route::post('/cardbalance/get', [AbonentController::class, 'checkCardBalance']);

Route::post('/abonent/listtarifV2', [AbonentController::class, 'listTarifV2']);

Route::post('/abonent/listcard', [AbonentController::class, 'listCard']);
Route::post('/abonent/tvchannellist', [AbonentController::class, 'tvChannelList']);
Route::post('/abonent/changetarif', [AbonentController::class, 'changeTarif']);
Route::post('/abonent/smslist', [AbonentController::class, 'smsList']);
Route::post('/abonent/historylist', [AbonentController::class, 'historyList']);
Route::post('/zayavka/add', [AbonentController::class, 'addZayavka']);
Route::post('/abonent/discountlist', [AbonentController::class, 'discountList']);
Route::post('/abonent/discount', [AbonentController::class, 'discount']);
Route::post('/callback/order', [AbonentController::class, 'callbackOrder']);
Route::post('/payment/alif/init', [AbonentController::class, 'initAlifPayment']);

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

    Route::prefix('support')->group(function () {
        // Верхний текст

        Route::put('/page-text', [SupportPageTextController::class, 'update']);

        // FAQ

        Route::post('/faq', [SupportAbonentFaqController::class, 'store']);
        Route::put('/faq/{faq}', [SupportAbonentFaqController::class, 'update']);
        Route::delete('/faq/{faq}', [SupportAbonentFaqController::class, 'destroy']);
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

    // Package
    Route::controller(\App\Http\Controllers\PackageController::class)->prefix('packages')->group(function () {
        Route::post('/', 'store');
        Route::put('/{package}', 'update');
        Route::delete('/{package}', 'destroy');
    });

    // PackageAttribute
    Route::controller(\App\Http\Controllers\PackageAttrController::class)->prefix('package-attributes')->group(function () {
        Route::post('/', 'store');
        Route::put('/{packageAttribute}', 'update');
        Route::delete('/{packageAttribute}', 'destroy');
    });

    // PackageFeature

    Route::controller(\App\Http\Controllers\PackageFeatureController::class)->prefix('package-features')->group(function () {
        Route::post('/', 'store');
        Route::put('/{packageFeature}', 'update');
        Route::delete('/{packageFeature}', 'destroy');
    });

    // Promotion

    Route::controller(\App\Http\Controllers\PromotionController::class)->prefix('promotions')->group(function () {
        Route::post('/', 'store');
        Route::put('/{promotion}', 'update');
        Route::delete('/{promotion}', 'destroy');
    });
        // kp
    Route::controller(\App\Http\Controllers\KpFileController::class)->prefix('kp')->group(function () {
            Route::post('/', 'store');
            Route::put('/{kpFile}', 'update');
            Route::delete('/{kpFile}', 'destroy');
        });

    // region
    Route::controller(\App\Http\Controllers\RegionController::class)->prefix('regions')->group(function () {
        Route::post('/', 'store');
        Route::put('/{region}', 'update');
        Route::delete('/{region}', 'destroy');
    });

    Route::controller(\App\Http\Controllers\ServiceCenterController::class)->prefix('service-centers')->group(function () {
        Route::post('/', 'store');
        Route::put('/{serviceCenter}', 'update');
        Route::delete('/{serviceCenter}', 'destroy');
    });
    Route::controller(\App\Http\Controllers\ServiceCenterController::class)->prefix('service-centers')->group(function () {
        Route::post('/', 'store');
        Route::put('/{serviceCenter}', 'update');
        Route::delete('/{serviceCenter}', 'destroy');
    });

    Route::controller(\App\Http\Controllers\ContactInfoController::class)->prefix('contacts')->group(function () {
        Route::post('/', 'store');
        Route::put('/{contactInfo}', 'update');
        Route::delete('/{contactInfo}', 'destroy');
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

//Banner
Route::controller(\App\Http\Controllers\BannerController::class)->prefix('/banners')->group(function () {
    Route::get('/', 'index');
    Route::get('/{type}', 'show');
});

// Package
Route::controller(\App\Http\Controllers\PackageController::class)->prefix('/packages')->group(function () {
    Route::get('/', 'index');
    Route::get('/{package:slug}', 'show');
});

// ChannelSetting
Route::controller(\App\Http\Controllers\ChannelSettingController::class)->prefix('/channel-settings')->group(function () {
    Route::post('/', 'store');
    Route::put('/{channelSetting}', 'update');
    Route::delete('/{channelSetting}', 'destroy');
});

// PackageAttribute
Route::controller(\App\Http\Controllers\PackageAttrController::class)->prefix('/package-attributes')->group(function () {
    Route::get('/{package}', 'index');
});

//promotion
Route::controller(\App\Http\Controllers\PromotionController::class)->prefix('/promotions')->group(function () {
    Route::get('/', 'index');
    Route::get('/get-by/{promotion:slug}','show');

});

// advertising rate
Route::controller(\App\Http\Controllers\AdvertisingRateController::class)->prefix('/advertising-rates')->group(function () {
    Route::post('/', 'store');
    Route::put('/{advertisingRate}', 'update');
    Route::delete('/{advertisingRate}', 'destroy');
});

//  PromotionCondition

Route::controller(\App\Http\Controllers\PromotionConditionController::class)->prefix('/promotion-contions')->group(function () {
    Route::post('/', 'store');
    Route::put('/{promotionCondition}', 'update');
    Route::delete('/{promotionCondition}', 'destroy');
});



// CompanyInfo

Route::controller(\App\Http\Controllers\CompanyInfoController::class)->prefix('/company-infos')->group(function () {
    Route::post('/', 'store');
    Route::put('/{companyInfo}', 'update');
    Route::delete('/{companyInfo}', 'destroy');
});

// Statistic

Route::controller(\App\Http\Controllers\StatisticController::class)->prefix('/statistics')->group(function () {
    Route::post('/', 'store');
    Route::put('/{statistic}', 'update');
    Route::delete('/{statistic}', 'destroy');
});

// Advantage

Route::controller(\App\Http\Controllers\AdvantageController::class)->prefix('/advantages')->group(function () {
    Route::post('/', 'store');
    Route::put('/{advantage}', 'update');
    Route::delete('/{advantage}', 'destroy');
});

//  PromotionPrizes

Route::controller(\App\Http\Controllers\PromotionPrizeController::class)->prefix('/promotion-prizes')->group(function () {
    Route::post('/', 'store');
    Route::put('/{promotionPrize}', 'update');
    Route::delete('/{promotionPrize}', 'destroy');
});

//  PromotionDrawn

Route::controller(\App\Http\Controllers\PromotionDrawController::class)->prefix('/promotion-draws')->group(function () {
    Route::post('/', 'store');
    Route::put('/{promotionDraw}', 'update');
    Route::delete('/{promotionDraw}', 'destroy');
});

// PromotionNote

Route::controller(\App\Http\Controllers\PromotionNoteController::class)->prefix('/promotion-notes')->group(function () {
    Route::post('/', 'store');
    Route::put('/{promotionNode}', 'update');
    Route::delete('/{promotionNode}', 'destroy');
});

// PromotionWinner

Route::controller(\App\Http\Controllers\PromotionWinnerController::class)->prefix('/promotion-winners')->group(function () {
    Route::post('/', 'store');
    Route::put('/{promotionWinner}', 'update');
    Route::delete('/{promotionWinner}', 'destroy');
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

Route::get('/kp', [\App\Http\Controllers\KpFileController::class, 'index']);

Route::get('/projects/{project:slug}', [\App\Http\Controllers\ProjectController::class, 'show']);

// ProjectImage
Route::get('/project-images/{project:slug}', [\App\Http\Controllers\ProjectImageController::class, 'index']);

// Language
Route::get('/languages', [\App\Http\Controllers\LanguageController::class, 'index']);

Route::get('/regions', [\App\Http\Controllers\RegionController::class, 'index']);

Route::get('/service-centers', [\App\Http\Controllers\ServiceCenterController::class, 'index']);

Route::get('/contacts', [\App\Http\Controllers\ContactInfoController::class, 'index']);

// AdvertisingRate
Route::get('/advertising-rates', [\App\Http\Controllers\AdvertisingRateController::class, 'index']);

Route::get('/promotion-contions', [\App\Http\Controllers\PromotionConditionController::class, 'index']);

Route::get('/promotion-prizes', [\App\Http\Controllers\PromotionPrizeController::class, 'index']);

Route::get('/promotion-draws', [\App\Http\Controllers\PromotionDrawController::class, 'index']);

Route::get('/promotion-notes', [\App\Http\Controllers\PromotionNoteController::class, 'index']);

Route::get('/promotion-winners', [\App\Http\Controllers\PromotionWinnerController::class, 'index']);

// CompanyInfo

Route::get('/company-infos', [\App\Http\Controllers\CompanyInfoController::class, 'index']);
Route::get('/company-infos/{companyInfo}', [\App\Http\Controllers\CompanyInfoController::class, 'show']);

// Statistic

Route::get('/statistics', [\App\Http\Controllers\StatisticController::class, 'index']);

// ChannelSetting

Route::get('/channel-settings', [\App\Http\Controllers\ChannelSettingController::class, 'index']);
Route::get('/channel-settings/{channelSetting}', [\App\Http\Controllers\ChannelSettingController::class, 'show']);
// Advantage
Route::get('/advantages', [\App\Http\Controllers\AdvantageController::class, 'index']);
Route::get('/advantages/{advantage}', [\App\Http\Controllers\AdvantageController::class, 'show']);
Route::get('/package-features/{package}', [\App\Http\Controllers\PackageFeatureController::class, 'index']);
Route::get('/support/faq', [SupportAbonentFaqController::class, 'index']);
Route::get('/support/page-text', [SupportPageTextController::class, 'show']);
