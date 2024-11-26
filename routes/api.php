<?php
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductLikeViewController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\SlideShowController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VnPayController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteInfoController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Auth\LoginController;


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
    Route::get('/', [UserController::class, 'index']);
});

// colors
Route::prefix('colors')->group(function () {
    Route::get('/', [ColorController::class, 'index']);
    Route::post('/store', [ColorController::class, 'store']);
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

// products-like-view
Route::prefix('products-like-view')->group(function () {
    Route::get('/', [ProductLikeViewController::class, 'index']);
});

// products
Route::prefix('products')->group(function () {
    Route::get('/cus/{id}', [ProductController::class, 'getByIdClient']);
    Route::get('/variant-list', [ProductController::class, 'getCategoriesAndVariants']);
    Route::post('/search-product', [ProductController::class, 'searchProduct']);
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/{id}', [ProductController::class, 'getById']);
    Route::post('/{id}', [ProductController::class, 'update']);
    Route::delete('/destroy/{id}', [ProductController::class, 'destroy']);
});

// product detail
Route::prefix('product-detail')->group(function () {
    Route::get('/{id}', [ProductController::class, 'getByIdClient']);
});

// products customer
Route::prefix('customer/products')->group(function () {
    Route::get('/', [ProductController::class, 'getListClient']);
});

// products-like-view
Route::prefix('products-like-view')->group(function () {
    Route::get('/', [ProductLikeViewController::class, 'index']);
});

//size
Route::prefix('sizes')->group(function () {
    Route::get('/', [SizeController::class, 'index']);
    Route::post('/store', [SizeController::class, 'store']); // Thêm một kích thước mới
    Route::put('/update/{id}', [SizeController::class, 'update']); // Cập nhật thông tin một kích thước theo ID
    Route::delete('destroy/{id}', [SizeController::class, 'destroy']); // Xóa một kích thước theo ID
});

//slide
Route::prefix('slideshows')->group(function () {
    Route::get('/', [SlideShowController::class, 'index']);
    Route::post('/store', [SlideShowController::class, 'store']); // Thêm một kích thước mới
    Route::put('/update/{id}', [SlideShowController::class, 'update']); // Cập nhật thông tin một kích thước theo ID
    Route::delete('/destroy/{id}', [SlideShowController::class, 'destroy']); // Xóa một kích thước theo ID
});

//comments
Route::prefix('comments')->group(function () {
    Route::get('/', [CommentController::class, 'index']);
    Route::post('/{id}', [CommentController::class, 'delete']);
});

//satistics
Route::prefix('statitics')->group(function () {
    Route::get('/', [StatisticController::class, 'index']);
});

// Siteinfo
Route::prefix('site-info')->group(function () {
    Route::get('/', [SiteInfoController::class, 'index']); // Lấy danh sách thông tin site
    Route::post('/store/', [SiteInfoController::class, 'store']); // Thêm thông tin site
    Route::put('/update/{id}', [SiteInfoController::class, 'update']); // Cập nhật thông tin site
    Route::delete('/destroy/{id}', [SiteInfoController::class, 'destroy']); // Xóa thông tin site
});

//Contac
Route::prefix('contacts')->group(function () {
    Route::get('/', [ContactController::class, 'index']); // Lấy tất cả thông tin liên hệ
    Route::get('/{id}', [ContactController::class, 'show']); // Lấy thông tin liên hệ theo ID
    Route::put('/{id}', [ContactController::class, 'update']); // Cập nhật thông tin liên hệ theo ID
    Route::delete('/{id}', [ContactController::class, 'destroy']); // Xóa thông tin liên hệ theo ID
});


Route::prefix('services')->group(function () {
    Route::get('/', [ServiceController::class, 'index']); // Lấy tất cả dịch vụ
    Route::put('/{id}', [ServiceController::class, 'update']); // Cập nhật thông tin dịch vụ theo ID
});


//login
Route::prefix('login')->group(function () {
    Route::post('/', [LoginController::class, 'login']);
});

//blogs
Route::prefix('blogs')->group(function () {
    Route::get('/', [BlogController::class, 'index']);
    Route::get('/{id}', [BlogController::class, 'show']);
});

//blogs_category
Route::prefix('blogcategory')->group(function () {
    Route::get('/', [BlogCategoryController::class, 'index']);
});

//thanh toan

Route::post('/purchase', [VnPayController::class, 'purchase']);