<?php

use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

    // TODO delete in production 'auth'
Route::middleware(['throttle:1000,60', 'web', 'auth'])
    ->name('site.')
    ->group(
        function () {
            Route::get('/', 'App\Http\Controllers\Site\HomeController@page')->name('home');

            Route::get('/pet/{catalogslug}/{categoryslug}/tag/{tagslug}', \App\Http\Livewire\Site\Pages\CategoryPage::class)->name('tag');

            Route::get('/pet/{catalogslug}/{categoryslug}', \App\Http\Livewire\Site\Pages\CategoryPage::class)->name('category');

            Route::get('/pet/{catalogslug}', \App\Http\Livewire\Site\Pages\CatalogPage::class)->name('catalog');

            Route::get('/discounts', \App\Http\Livewire\Site\Pages\DiscountPage::class)->name('discounts');

            Route::get('/search', \App\Http\Livewire\Site\Search\SearchPage::class)->name('search');

            // Tabs routes of product page
            Route::get(
                '/pet/{catalogslug}/{categoryslug}/{productslug}',
                'App\Http\Controllers\Site\ProductController@show'
            )->name('product');

            Route::get(
                '/pet/{catalogslug}/{categoryslug}/{productslug}/consist',
                'App\Http\Controllers\Site\ProductController@showСonsist'
            )->name('product.consist');

            Route::get(
                '/pet/{catalogslug}/{categoryslug}/{productslug}/applying',
                'App\Http\Controllers\Site\ProductController@showApplying'
            )->name('product.applying');

            // Brands
            Route::get('/brands', \App\Http\Livewire\Site\Pages\BrandsPage::class)->name('brands');

            Route::get('/brands/{brandslug}', \App\Http\Livewire\Site\Pages\BrandPage::class)->name('brand');

            // Pages
            Route::get('/contact', \App\Http\Livewire\Site\Pages\ContactPage::class)->name('contact');

            Route::get('/page/{slug}', 'App\Http\Controllers\Site\PageController@show')->name('page');

            Route::get('/checkout/confirm', \App\Http\Livewire\Site\Checkout\CheckoutConfirm::class)
                ->middleware(['auth'])
                ->name('checkout.confirm');

            Route::get('/checkout/callback', 'App\Http\Controllers\Site\OrderController@checkoutCallback')->name('checkout.callback');
        }
    );

Route::middleware(['throttle:1000,60'])->group(
    function () {
        Route::get('/checkout', \App\Http\Livewire\Site\Checkout\Checkout::class)->name('checkout');

        Route::any('/payment/callback', 'App\Http\Controllers\Site\OrderController@yooKassaCallback')
        ->name('payment.callback'); // callback for YooKassa
    }
);

        // 1С exchange
Route::any('/exchange', 'App\Http\Controllers\Dashboard\ExchangeProductsController@exchange')
    ->middleware(['throttle:1000,60', 'bot'])
    ->name('exchange');


        // Account
Route::prefix('account')->name('account.')->middleware(['throttle:1000,60', 'auth'])->group(
    function () {
        Route::view('/account', 'site.account.account')->name('account');

        Route::get('/profile', \App\Http\Livewire\Site\Account\ProfilePage::class)->name('profile');

        Route::PATCH('/profile/{id}', 'App\Http\Controllers\Site\AccountController@profileUpdate')
        ->name('user.update');

        Route::get('/favorites', \App\Http\Livewire\Site\Account\FavoritesPage::class)->name('favorites');

        Route::get('/orders', 'App\Http\Controllers\Site\OrderController@orders')->name('orders');

        Route::get('/orders/order', \App\Http\Livewire\Site\Account\OrderPage::class)->name('order');
    }
);

        // Dashboard
Route::prefix('dashboard')->name('dashboard.')->middleware(['throttle:1000,60', 'auth', 'permission:dashboard'])->group(
    function () {
        Route::get('/', \App\Http\Livewire\Dashboard\Dashboard::class)->name('dashboard');

        Route::middleware(['permission: admin'])->group(
            function () {
                Route::get('/permissions', \App\Http\Livewire\Dashboard\Permissions::class)->name('permissions');

                Route::get('/roles', \App\Http\Livewire\Dashboard\Roles::class)->name('roles');

                Route::get('/users', \App\Http\Livewire\Dashboard\Users::class)->name('users');

                Route::get('/logs', \App\Http\Livewire\Dashboard\LogsViewer::class)->name('logs');

                Route::prefix('settings')->name('settings.')->group(
                    function () {
                        Route::get('/backup', \App\Http\Livewire\Dashboard\Settings\Backup::class)->name('backup');
                        Route::get('/main', \App\Http\Livewire\Dashboard\Settings\Main::class)->name('main');
                    }
                );
            }
        );

        Route::get('/tags', \App\Http\Livewire\Dashboard\Tags::class)->name('tags');

        Route::get('/attributes', \App\Http\Livewire\Dashboard\Attributes::class)->name('attributes');

        Route::get('/brands', \App\Http\Livewire\Dashboard\Brands::class)->name('brands');

        Route::get('/units', \App\Http\Livewire\Dashboard\Units::class)->name('units');

        Route::get('/catalogs', \App\Http\Livewire\Dashboard\Catalogs::class)->name('catalogs');

        Route::get('/variations', \App\Http\Livewire\Dashboard\Products1c::class)->name('products1c');

        Route::get('/products', \App\Http\Livewire\Dashboard\Products\Index::class)->name('products.index');

        Route::get('products/edit', \App\Http\Livewire\Dashboard\Products\Edit::class)->name('product.edit');

        Route::get('/pages', \App\Http\Livewire\Dashboard\Pages\Pages::class)->name('pages');

        Route::prefix('page')->group(
            function () {
                Route::get('/home', \App\Http\Livewire\Dashboard\Pages\Home::class)->name('home.edit');

                Route::get('/edit', \App\Http\Livewire\Dashboard\Pages\PageEdit::class)->name('page.edit');
            }
        );

        Route::get('/reviews', \App\Http\Livewire\Dashboard\Reviews::class)->name('reviews');

        Route::get('/orders', \App\Http\Livewire\Dashboard\Orders::class)->name('orders');

        Route::get('/waitlists', \App\Http\Livewire\Dashboard\Waitlists::class)->name('waitlists');

        Route::get('/excel', \App\Http\Livewire\Dashboard\Excel::class)->name('excel');
    }
);
