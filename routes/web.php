<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer\ShopController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Customer\AccountController;
use App\Http\Controllers\Customer\WalletController;
use App\Http\Controllers\Customer\RewardPointController;
use App\Http\Controllers\Customer\PageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\VendorController as AdminVendorController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\RewardPointController as AdminRewardPointController;
use App\Http\Controllers\Admin\WalletController as AdminWalletController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Vendor\DashboardController as VendorDashboardController;
use App\Http\Controllers\Vendor\ProductController as VendorProductController;
use App\Http\Controllers\Vendor\VariantController;
use App\Http\Controllers\Vendor\OrderController as VendorOrderController;
use App\Http\Controllers\Vendor\WalletController as VendorWalletController;
use App\Http\Controllers\Vendor\SubscriptionController;
use App\Http\Controllers\Vendor\CouponController as VendorCouponController;
use App\Http\Controllers\WebhookController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Language Switch
Route::post('/locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es', 'fr', 'de', 'hi'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return response()->json(['success' => true]);
})->name('locale.change');

// Home & Shop
Route::get('/', [ShopController::class, 'home'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/search', [ShopController::class, 'search'])->name('shop.search');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/vendor/{slug}', [ShopController::class, 'vendorShop'])->name('shop.vendor');

// CMS Pages
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/vendor/register', [AuthController::class, 'showVendorRegisterForm'])->name('vendor.register');
    Route::post('/vendor/register', [AuthController::class, 'vendorRegister']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Cart (accessible to guests and authenticated users)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{itemId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{itemId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::delete('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
Route::post('/cart/rewards/apply', [CartController::class, 'applyRewardPoints'])->name('cart.rewards.apply')->middleware('auth');

// Webhooks (no CSRF)
Route::post('/webhooks/stripe', [WebhookController::class, 'stripeWebhook'])->name('webhooks.stripe');
Route::post('/webhooks/razorpay', [WebhookController::class, 'razorpayWebhook'])->name('webhooks.razorpay');

/*
|--------------------------------------------------------------------------
| Authenticated Customer Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel/{order}', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    Route::post('/checkout/razorpay/callback', [CheckoutController::class, 'razorpayCallback'])->name('checkout.razorpay.callback');

    // Orders
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/track', [CustomerOrderController::class, 'track'])->name('orders.track');
    Route::get('/orders/{order}/invoice', [CustomerOrderController::class, 'invoice'])->name('orders.invoice');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/{wishlist}/move-to-cart', [WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart');

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Account
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::put('/account', [AccountController::class, 'update'])->name('account.update');
    Route::put('/account/password', [AccountController::class, 'changePassword'])->name('account.password');

    // Wallet
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');

    // Reward Points
    Route::get('/reward-points', [RewardPointController::class, 'index'])->name('reward-points.index');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Vendors
    Route::get('/vendors', [AdminVendorController::class, 'index'])->name('vendors.index');
    Route::get('/vendors/{vendor}', [AdminVendorController::class, 'show'])->name('vendors.show');
    Route::post('/vendors/{vendor}/approve', [AdminVendorController::class, 'approve'])->name('vendors.approve');
    Route::post('/vendors/{vendor}/reject', [AdminVendorController::class, 'reject'])->name('vendors.reject');
    Route::post('/vendors/{vendor}/suspend', [AdminVendorController::class, 'suspend'])->name('vendors.suspend');
    Route::put('/vendors/{vendor}/commission', [AdminVendorController::class, 'updateCommission'])->name('vendors.commission');

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/{order}/refund', [AdminOrderController::class, 'refund'])->name('orders.refund');

    // Products
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [AdminProductController::class, 'show'])->name('products.show');
    Route::post('/products/{product}/featured', [AdminProductController::class, 'toggleFeatured'])->name('products.featured');
    Route::post('/products/{product}/status', [AdminProductController::class, 'toggleStatus'])->name('products.status');

    // Coupons
    Route::resource('coupons', AdminCouponController::class);

    // Categories
    Route::resource('categories', CategoryController::class);

    // Brands
    Route::resource('brands', BrandController::class);

    // Attributes
    Route::resource('attributes', AttributeController::class);

    // Reward Points
    Route::get('/reward-points', [AdminRewardPointController::class, 'index'])->name('reward-points.index');
    Route::get('/reward-points/create', [AdminRewardPointController::class, 'create'])->name('reward-points.create');
    Route::post('/reward-points', [AdminRewardPointController::class, 'store'])->name('reward-points.store');
    Route::get('/reward-points/{user}', [AdminRewardPointController::class, 'show'])->name('reward-points.show');

    // Wallets
    Route::get('/wallets', [AdminWalletController::class, 'index'])->name('wallets.index');
    Route::get('/wallets/create', [AdminWalletController::class, 'create'])->name('wallets.create');
    Route::post('/wallets', [AdminWalletController::class, 'store'])->name('wallets.store');
    Route::get('/wallets/{user}', [AdminWalletController::class, 'show'])->name('wallets.show');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/settings/create', [SettingController::class, 'create'])->name('settings.create');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    Route::delete('/settings/{setting}', [SettingController::class, 'destroy'])->name('settings.destroy');

    // Subscription Plans
    Route::resource('plans', SubscriptionPlanController::class);

    // CMS Pages
    Route::resource('pages', AdminPageController::class);

    // Media Manager
    Route::get('/media', [AdminMediaController::class, 'index'])->name('media.index');
    Route::post('/media', [AdminMediaController::class, 'store'])->name('media.store');
    Route::delete('/media/{medium}', [AdminMediaController::class, 'destroy'])->name('media.destroy');
});

/*
|--------------------------------------------------------------------------
| Vendor Routes
|--------------------------------------------------------------------------
*/

Route::prefix('vendor')->name('vendor.')->middleware(['auth', 'role:vendor'])->group(function () {
    Route::get('/pending', [VendorDashboardController::class, 'pending'])->name('pending');

    Route::middleware('vendor.approved')->group(function () {
        Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');

        // Products
        Route::resource('products', VendorProductController::class);
        Route::delete('/products/image/{image}', [VendorProductController::class, 'deleteImage'])->name('products.image.delete');

        // Variants
        Route::post('/products/{product}/variants', [VariantController::class, 'store'])->name('variants.store');
        Route::put('/variants/{variant}', [VariantController::class, 'update'])->name('variants.update');
        Route::delete('/variants/{variant}', [VariantController::class, 'destroy'])->name('variants.destroy');

        // Orders
        Route::get('/orders', [VendorOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [VendorOrderController::class, 'show'])->name('orders.show');

        // Wallet
        Route::get('/wallet', [VendorWalletController::class, 'index'])->name('wallet.index');

        // Subscription
        Route::get('/subscription/plans', [SubscriptionController::class, 'plans'])->name('subscription.plans');
        Route::post('/subscription/{plan}/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
        Route::post('/subscription/{plan}/confirm', [SubscriptionController::class, 'confirmSubscription'])->name('subscription.confirm');

        // Coupons
        Route::get('/coupons', [VendorCouponController::class, 'index'])->name('coupons.index');
        Route::post('/coupons', [VendorCouponController::class, 'store'])->name('coupons.store');
        Route::delete('/coupons/{coupon}', [VendorCouponController::class, 'destroy'])->name('coupons.destroy');
    });
});
