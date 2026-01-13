<?php

use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route('menu.show', ['table' => 1]);
});

Route::get('/menu/{table?}', [MenuController::class, 'show'])->name('menu.show');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/{order}/status', [OrderController::class, 'status'])->name('order.status');
Route::post('/call-waiter', [OrderController::class, 'callWaiter'])->name('order.call-waiter');
Route::get('/payment/{order}/pay', [App\Http\Controllers\PaymentController::class, 'pay'])->name('payment.pay');
Route::match(['get', 'post'], '/payment/callback', [App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.callback');
Route::get('/payment/{order}/success', [App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'km'])) {
        session()->put('locale', $locale);
    }
    return back();
})->name('lang.switch');

Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // User Management (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    });

    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::patch('products/{product}/toggle', [App\Http\Controllers\Admin\ProductController::class, 'toggleAvailability'])->name('products.toggle');
    Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('kitchen', [App\Http\Controllers\Admin\OrderController::class, 'kitchen'])->name('orders.kitchen');
    Route::patch('orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'update'])->name('orders.update');
    Route::patch('orders/{order}/dismiss-waiter', [App\Http\Controllers\Admin\OrderController::class, 'dismissWaiter'])->name('orders.dismiss-waiter');
    Route::get('orders/{order}/print', [App\Http\Controllers\Admin\OrderController::class, 'print'])->name('orders.print');
    Route::get('api/check-orders', function() {
        return response()->json([
            'count' => \App\Models\Order::where('status', 'pending')->count(),
            'waiter_calls' => \App\Models\Order::where('is_calling_waiter', true)->count()
        ]);
    });

    Route::post('tables/{table}/toggle-status', [App\Http\Controllers\Admin\TableController::class, 'toggleStatus'])->name('tables.toggle-status');
    Route::post('tables/{table}/checkout', [App\Http\Controllers\Admin\TableController::class, 'checkout'])->name('tables.checkout');
    Route::post('tables/{table}/add-item', [App\Http\Controllers\Admin\TableController::class, 'addItem'])->name('tables.add-item');
    Route::resource('tables', App\Http\Controllers\Admin\TableController::class);
});
