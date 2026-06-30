<?php

use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\OrderController;
use App\Models\Product;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

Route::get('/', function () {
    $products = Product::latest()->get();
    return view('welcome', compact('products'));
})->name('home');

Route::get('/products/{product:slug}', function (Product $product) {
    return view('products.show', compact('product'));
})->name('products.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/dashboard', function () {
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('buyer.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/buyer/dashboard', function () {
    return view('buyer.dashboard');
})->middleware(['auth', 'verified'])->name('buyer.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Chat routes
Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/fetch/{user}', [ChatController::class, 'fetchMessages'])->name('chat.fetch');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
});

// Checkout & Orders (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/proof', [OrderController::class, 'uploadProof'])->name('orders.proof');
    Route::get('/download/{order}/{product}', [DownloadController::class, 'download'])->name('download.product');
});

// Admin dashboard
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->middleware(['auth', 'admin'])->name('admin.dashboard');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', AdminProductController::class);
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/verify', [AdminOrderController::class, 'verify'])->name('orders.verify');
    Route::post('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/docs', function () {
        return view('admin.docs.index');
    })->name('docs');
    // Update Web from GitHub
    Route::get('/update-web', [\App\Http\Controllers\Admin\UpdateWebController::class, 'index'])->name('update-web.index');
    Route::post('/update-web/save-token', [\App\Http\Controllers\Admin\UpdateWebController::class, 'saveToken'])->name('update-web.save-token');
    Route::post('/update-web/do-update', [\App\Http\Controllers\Admin\UpdateWebController::class, 'update'])->name('update-web.do-update');

    // WhatsApp Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/test-whatsapp', [SettingController::class, 'testWhatsApp'])->name('settings.test-whatsapp');
});

// Midtrans Webhook (no CSRF)
Route::post('/api/midtrans-callback', [CheckoutController::class, 'notification'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Static Pages
Route::get('/syarat-ketentuan', function () {
    return view('terms');
})->name('terms');

// Custom Error Pages
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

require __DIR__.'/auth.php';
