<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Ecommerce\FrontController;
use App\Http\Controllers\Ecommerce\CartController;
use App\Http\Controllers\Ecommerce\LoginController;
use App\Http\Controllers\Ecommerce\OrderController;
use App\Imports\ProductImport;
use App\Jobs\ProductJob;
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


Auth::routes();

Route::get('/', [App\Http\Controllers\Ecommerce\FrontController::class,'index'])->name('front.index');
Route::get('/produk', [App\Http\Controllers\Ecommerce\FrontController::class,'product'])->name('front.product');
Route::get('/category/{slug}', [App\Http\Controllers\Ecommerce\FrontController::class,'categoryProduct'])->name('front.category');
Route::get('/product/{slug}', [App\Http\Controllers\Ecommerce\FrontController::class,'show'])->name('front.show_product');
Route::get('/product/ref/{user}/{product}', [App\Http\Controllers\Ecommerce\FrontController::class,'referalProduct'])->name('front.afiliasi');

Route::get('/cart', [App\Http\Controllers\Ecommerce\CartController::class,'listCart'])->name('front.list_cart');
Route::post('/cart', [App\Http\Controllers\Ecommerce\CartController::class,'addToCart'])->name('front.cart');
Route::post('/cart/update', [App\Http\Controllers\Ecommerce\CartController::class,'updateCart'])->name('front.update_cart');


Route::get('/checkout', [App\Http\Controllers\Ecommerce\CartController::class,'checkout'])->name('front.checkout');
Route::post('/checkout', [App\Http\Controllers\Ecommerce\CartController::class,'processCheckout'])->name('front.store_checkout');
Route::get('/checkout/{invoice}', [App\Http\Controllers\Ecommerce\CartController::class,'checkoutFinish'])->name('front.finish_checkout');

Route::group(['prefix' =>  'member', 'namespace' => 'Ecommerce'], function()
{
    Route::get('login', [App\Http\Controllers\Ecommerce\LoginController::class, 'loginForm'])->name('customer.login');
    Route::post('login', [App\Http\Controllers\Ecommerce\LoginController::class, 'login'])->name('customer.post_login');
    Route::get('verify/{token}', [App\Http\Controllers\Ecommerce\FrontController::class, 'verifyCustomerRegistration'])->name('customer.verify');

Route::group(['middleware' => 'customer'],function(){
    Route::get('dashboard', [App\Http\Controllers\Ecommerce\LoginController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('logout', [App\Http\Controllers\Ecommerce\LoginController::class, 'logout'])->name('customer.logout');
    Route::get('orders', [App\Http\Controllers\Ecommerce\OrderController::class, 'index'])->name('customer.orders');
    Route::get('orders/{invoice}', [App\Http\Controllers\Ecommerce\OrderController::class, 'view'])->name('customer.view_order');
    Route::get('orders/pdf/{invoice}', [App\Http\Controllers\Ecommerce\OrderController::class, 'pdf'])->name('customer.order_pdf');
    Route::get('payment', [App\Http\Controllers\Ecommerce\OrderController::class, 'paymentForm'])->name('customer.paymentForm');
    Route::post('payment', [App\Http\Controllers\Ecommerce\OrderController::class, 'storePayment'])->name('customer.savePayment');
    Route::get('setting', [App\Http\Controllers\Ecommerce\FrontController::class, 'customerSettingForm'])->name('customer.settingForm');
    Route::post('setting', [App\Http\Controllers\Ecommerce\FrontController::class, 'customerUpdateForm'])->name('customer.setting');
    Route::get('/afiliasi', [App\Http\Controllers\Ecommerce\FrontController::class, 'listCommission'])->name('customer.affiliate');


});
});

Route::group(['prefix' =>  'administrator', 'middleware' => 'auth'], function(){
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    Route::resource('category', CategoryController::class) -> except(['create', 'show']);
    
    Route::resource('product', ProductController::class) -> except(['show']);
    Route::get('/product/bulk', [ProductController::class,'massUploadForm'])->name('product.bulk');
    Route::post('/product/bulk', [ProductController::class,'massUpload'])->name('product.savebulk');

    Route::group(['prefix' => 'orders'], function(){
        Route::get('/', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
        Route::get('/{invoice}', [App\Http\Controllers\OrderController::class, 'view'])->name('orders.view');
        Route::get('/payment/{invoice}', [App\Http\Controllers\OrderController::class, 'acceptPayment'])->name('orders.approve_payment');
        Route::post('/shipping', [App\Http\Controllers\OrderController::class, 'shippingOrder'])->name('orders.shipping');
        Route::delete('/{id}', [App\Http\Controllers\OrderController::class, 'destroy'])->name('orders.destroy');
    });
    

   
});
