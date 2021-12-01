<?php

use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

    // Account
Route::prefix('account')->name('account.')->middleware(['throttle:1000,60', 'auth'])->group(
    function () {
        Route::get('/profile', 'App\Http\Controllers\Site\AccountController@profile')->name('profile');

        Route::PATCH('/profile/{id}', 'App\Http\Controllers\Site\AccountController@profileUpdate')
        ->name('user.update');

        Route::view('/favorites', 'site.account.favorites')->name('favorites');

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

    // TODO delete in production 'auth'
Route::middleware(['throttle:1000,60', 'auth'])
    ->name('site.')
    ->group(
        function () {
            Route::get('/', 'App\Http\Controllers\Site\HomeController@index')->name('home');

            Route::get('/pet/{catalog}/{slug}/f/{tagslug}', 'App\Http\Controllers\Site\CategoryController@tag')
            ->name('tag');

            Route::get('/pet/{catalog}/{slug}', 'App\Http\Controllers\Site\CategoryController@show')
            ->name('category');

            Route::get('/pet/{slug}', \App\Http\Livewire\Site\CatalogPage::class)->name('catalog');

            Route::get('/search', \App\Http\Livewire\Site\Search\SearchPage::class)->name('search');

            // Tabs routes of product page
            Route::get(
                '/pet/{catalog}/{category}/{slug}',
                'App\Http\Controllers\Site\ProductController@show'
            )->name('product');

            Route::get(
                '/pet/{catalog}/{category}/{slug}/consist',
                'App\Http\Controllers\Site\ProductController@showСonsist'
            )->name('product.consist');

            Route::get(
                '/pet/{catalog}/{category}/{slug}/applying',
                'App\Http\Controllers\Site\ProductController@showApplying'
            )->name('product.applying');

            // Brands
            Route::get('/brands', 'App\Http\Controllers\Site\BrandController@index')->name('brands');

            Route::get('/brands/{brand}', 'App\Http\Controllers\Site\BrandController@show')->name('brand');

            // Pages
            Route::view('/contact', 'site.pages.contact')->name('contact');

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
