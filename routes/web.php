<?php

use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

const THROTTLE = 'throttle:1000,60';

    // Account
Route::prefix('account')->middleware([THROTTLE, 'auth'])->group(function () {
    Route::get('/profile', 'App\Http\Controllers\Site\AccountController@profile')->name('account.profile');

    Route::get('/orders', 'App\Http\Controllers\Site\AccountController@orders')->name('account.orders');

    Route::view('/favorites', 'site.account.favorites')->name('account.favorites');

    Route::PATCH('/profile/{id}', 'App\Http\Controllers\Site\AccountController@profileUpdate')->name('account.user.update');
});

    // Admin
Route::prefix('dashboard')->name('dashboard.')->middleware([THROTTLE, 'auth', 'permission:dashboard'])->group(function () {
    Route::get('/', \App\Http\Livewire\Dashboard\Dashboard::class)->name('dashboard');

    Route::middleware(['permission: admin'])->group(function () {
        Route::get('/permissions', \App\Http\Livewire\Dashboard\Permissions::class)->name('permissions');

        Route::get('/roles', \App\Http\Livewire\Dashboard\Roles::class)->name('roles');

        Route::get('/users', \App\Http\Livewire\Dashboard\Users::class)->name('users');

        Route::get('/settings', \App\Http\Livewire\Dashboard\Settings::class)->name('settings');

        Route::get('/logs', \App\Http\Livewire\Dashboard\LogsViewer::class)->name('logs');
    });

    Route::get('/tags', \App\Http\Livewire\Dashboard\Tags::class)->name('tags');

    Route::get('/attributes', \App\Http\Livewire\Dashboard\Attributes::class)->name('attributes');

    Route::get('/brands', \App\Http\Livewire\Dashboard\Brands::class)->name('brands');

    Route::get('/units', \App\Http\Livewire\Dashboard\Units::class)->name('units');

    Route::get('/catalogs', \App\Http\Livewire\Dashboard\Catalogs::class)->name('catalogs');

    Route::get('/variations', \App\Http\Livewire\Dashboard\Products1c::class)->name('products1c');

    Route::get('/products', \App\Http\Livewire\Dashboard\Products\Index::class)->name('products.index');

    Route::get('products/edit', \App\Http\Livewire\Dashboard\Products\Edit::class)->name('product.edit');

    Route::get('/pages', \App\Http\Livewire\Dashboard\Pages\Pages::class)->name('pages');

    Route::prefix('page')->group(function () {
        Route::get('/home', \App\Http\Livewire\Dashboard\Pages\Home::class)->name('home.edit');

        Route::get('/edit', \App\Http\Livewire\Dashboard\Pages\PageEdit::class)->name('page.edit');
    });

    Route::get('/reviews', \App\Http\Livewire\Dashboard\Reviews::class)->name('reviews');

    Route::get('/orders', \App\Http\Livewire\Dashboard\Orders::class)->name('orders');

    Route::get('/auto-orders', \App\Http\Livewire\Dashboard\AutoOrders::class)->name('autoorders');

    Route::get('/excel/product', 'App\Http\Controllers\Dashboard\ExcelController@exportProducts')->name('excel.product');
    Route::get('/excel/product1c', 'App\Http\Controllers\Dashboard\ExcelController@exportProducts1C')->name('excel.product1c');

    Route::get('/excel', \App\Http\Livewire\Dashboard\Exchange::class)->name('excel');
});

    // TODO delete in production 'auth'
    Route::middleware([THROTTLE, 'auth'])
    ->name('site.')
    ->group(function () {
        Route::get('/', 'App\Http\Controllers\Site\HomeController@index')->name('home');

        Route::get('/pet/{catalog}/{slug}/f/{tagslug}', 'App\Http\Controllers\Site\CategoryController@tag')
            ->name('tag');

        Route::get('/pet/{catalog}/{slug}', 'App\Http\Controllers\Site\CategoryController@show')
            ->name('category');

        Route::get('/pet/{slug}', \App\Http\Livewire\Site\CatalogPage::class)->name('catalog');

        // Tabs routes of product page
        Route::get('/pet/{catalog}/{category}/{slug}', 'App\Http\Controllers\Site\ProductController@show')->name('product');

        Route::get('/pet/{catalog}/{category}/{slug}/consist', 'App\Http\Controllers\Site\ProductController@showСonsist')->name('product.consist');

        Route::get('/pet/{catalog}/{category}/{slug}/applying', 'App\Http\Controllers\Site\ProductController@showApplying')->name('product.applying');

        // Brands
        Route::get('/brands', 'App\Http\Controllers\Site\BrandController@index')->name('brands');

        Route::get('/brands/{brand}', 'App\Http\Controllers\Site\BrandController@show')->name('brand');

        // Pages
        Route::view('/shops', 'site.pages.shops')->name('shops');

        Route::get('/page/{slug}', 'App\Http\Controllers\Site\PageController@show')->name('page');

        Route::get('/payment/cash', 'App\Http\Controllers\Site\PaymentController@payСash')->name('payment.cash');

        Route::get('/payment/status', 'App\Http\Controllers\Site\PaymentController@userCameBack')->name('payment.status');
    });

    // 1С exchange
Route::any('/exchange', 'App\Http\Controllers\Dashboard\ExchangeProductsController@exchange')->middleware(['throttle:1000,60', 'bot'])->name('exchange');

Route::middleware([THROTTLE, 'bot'])->group(function () {
    Route::get('/checkout', \App\Http\Livewire\Site\Checkout::class)->name('checkout');

    Route::post('/payment/callback', 'App\Http\Controllers\Site\PaymentController@payCallback')->name('payment.callback');
});
