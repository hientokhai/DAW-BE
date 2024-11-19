<?php
use App\Http\Controllers\ColorController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductLikeViewController;
use App\Http\Controllers\CategoryController;
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
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// users
Route::prefix('users')->group(function () {
    Route::get('/', function (Request $request) {
        return ["message" => "This is API for Users"];
    });
});

// colors
Route::prefix('colors')->group(function () {
    Route::get('/', [ColorController::class, 'index']);
    Route::post('/', [ColorController::class, 'store']);
    Route::put('/update/{id}', [ColorController::class, 'update']);
    Route::delete('/destroy/{id}', [ColorController::class, 'destroy']);
});

// orders
Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'getOrderById']);
    Route::put('/{id}/status', [OrderController::class, 'updateOrderStatus']);
    Route::delete('/{id}', [OrderController::class, 'destroy']);
});

//categoryD
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/store', [CategoryController::class, 'store']);
    Route::get('/search', [CategoryController::class, 'search']);
    Route::get('/show/{id}', [CategoryController::class, 'show']);
    Route::put('/update/{id}', [CategoryController::class, 'update']);
    Route::delete('/destroy/{id}', [CategoryController::class, 'destroy']);
});

// products
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
});


// products-like-view
Route::prefix('products-like-view')->group(function () {
    Route::get('/', [ProductLikeViewController::class, 'index']);
});

