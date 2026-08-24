<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\PaymentController;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Payment\CryptoWebhookController;
use Illuminate\Support\Facades\Route;

// ========== LANGUE ==========
Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['fr', 'en', 'ar'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// ========== SHOP ==========
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/categorie/{category:slug}', [ShopProductController::class, 'index'])->name('shop.category');
Route::get('/produit/{product:slug}', [ShopProductController::class, 'show'])->name('shop.product');

Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
Route::post('/panier/ajouter/{variant}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/panier/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/panier/{item}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/commande', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/commande', [CheckoutController::class, 'store'])->name('checkout.store');

// ========== SUPPORT ==========
Route::get('/support', fn() => view('shop.support'))->name('support');
Route::post('/support', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'name'         => 'required|string|max:255',
        'email'        => 'required|email',
        'subject'      => 'required|string|max:255',
        'message'      => 'required|string|min:10',
        'order_number' => 'nullable|string',
    ]);

    \App\Models\SupportMessage::create([
        'name'    => $request->name,
        'email'   => $request->email,
        'subject' => $request->subject,
        'message' => $request->message,
    ]);

    \Illuminate\Support\Facades\Mail::send('emails.support', [
        'name'         => $request->name,
        'email'        => $request->email,
        'subject'      => $request->subject,
        'content'      => $request->message,
        'order_number' => $request->order_number,
    ], function ($msg) use ($request) {
        $msg->to('brd.ghiles2004@gmail.com')
            ->subject('Support — ' . $request->subject . ' — ' . $request->name)
            ->replyTo($request->email, $request->name);
    });

    $admins = \App\Models\User::where('is_admin', true)->get();
    foreach ($admins as $admin) {
        $admin->notify(new \App\Notifications\NewFeedbackNotification(
            $request->name, $request->email, $request->subject, $request->message
        ));
    }

    return back()->with('success', 'Message envoyé ✅');
})->name('support.send');

// ========== PAYMENT ==========
Route::get('/commande/{order}/paiement', [PaymentController::class, 'show'])->name('payment.show');

Route::post('/commande/{order}/payer/crypto', [PaymentController::class, 'payCrypto'])->name('payment.crypto');
Route::get('/paiement/crypto/success', [PaymentController::class, 'cryptoSuccess'])->name('payment.crypto.success');
Route::post('/webhook/crypto', [PaymentController::class, 'cryptoWebhook'])
    ->name('payment.crypto.webhook')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::post('/commande/{order}/payer/satim', [PaymentController::class, 'paySatim'])->name('payment.satim');
Route::get('/paiement/satim/callback', [PaymentController::class, 'satimCallback'])->name('payment.satim.callback');

Route::post('/commande/{order}/payer/paypal', [PaymentController::class, 'payPaypal'])->name('payment.paypal');
Route::post('/commande/{order}/payer/cod', [PaymentController::class, 'payCod'])->name('payment.cod');
Route::post('/commande/{order}/payer/manual', [PaymentController::class, 'payManual'])->name('payment.manual');
Route::get('/commande/{order}/paiement/confirmation', [PaymentController::class, 'manualSuccess'])->name('payment.manual.success');

Route::post('/payments/webhook', [CryptoWebhookController::class, 'handle'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// ========== COMMUNES ==========
Route::get('/communes/{wilaya}', function (string $wilaya) {
    $communes = json_decode(file_get_contents(public_path('data/communes.json')), true);
    return response()->json($communes[$wilaya] ?? []);
})->name('communes.by.wilaya');

// ========== SITEMAP ==========
Route::get('/sitemap.xml', function () {
    $path = public_path('sitemap.xml');
    if (! file_exists($path)) {
        \Artisan::call('sitemap:generate');
    }
    return response()->file($path, ['Content-Type' => 'application/xml']);
})->name('sitemap');

// ========== USER ==========
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

// ========== VARIANTS STOCK ==========
Route::patch('/admin/variants/{variant}/stock', [\App\Http\Controllers\Admin\ProductController::class, 'updateStock'])
    ->middleware(['auth', 'admin'])
    ->name('admin.variants.stock');

// ========== ADMIN ==========
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/product-profits', [
        \App\Http\Controllers\Admin\ProductProfitController::class,
        'index'
    ])->name('product-profits.index');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/analytics/data', [AnalyticsController::class, 'data'])->name('analytics.data');

    Route::resource('products', AdminProductController::class);
    Route::resource('categories', AdminCategoryController::class);

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('users.toggle-admin');

    Route::get('/support', [\App\Http\Controllers\Admin\SupportController::class, 'index'])->name('support.index');
    Route::get('/support/{message}', [\App\Http\Controllers\Admin\SupportController::class, 'show'])->name('support.show');
    Route::post('/support/{message}/reply', [\App\Http\Controllers\Admin\SupportController::class, 'reply'])->name('support.reply');
    Route::delete('/support/{message}', [\App\Http\Controllers\Admin\SupportController::class, 'destroy'])->name('support.destroy');

    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'readAll'])->name('notifications.read-all');
    

    // داخل admin group
Route::post('/orders/bulk-ship', [\App\Http\Controllers\Admin\OrderController::class, 'bulkShip'])->name('orders.bulk-ship');
Route::post('/orders/{order}/ship', [\App\Http\Controllers\Admin\OrderController::class, 'shipSingle'])->name('orders.ship');
    // Integrations
    Route::get('/integrations', [\App\Http\Controllers\Admin\IntegrationController::class, 'index'])->name('integrations.index');
    Route::get('/integrations/delivery', [\App\Http\Controllers\Admin\IntegrationController::class, 'delivery'])->name('integrations.delivery');
    Route::post('/integrations/delivery', [\App\Http\Controllers\Admin\IntegrationController::class, 'saveDelivery'])->name('integrations.delivery.save');
    Route::get('/integrations/payment', [\App\Http\Controllers\Admin\IntegrationController::class, 'payment'])->name('integrations.payment');
    Route::post('/integrations/payment', [\App\Http\Controllers\Admin\IntegrationController::class, 'savePayment'])->name('integrations.payment.save');
    Route::get('/integrations/marketing', [\App\Http\Controllers\Admin\IntegrationController::class, 'marketing'])->name('integrations.marketing');
    Route::post('/integrations/marketing', [\App\Http\Controllers\Admin\IntegrationController::class, 'saveMarketing'])->name('integrations.marketing.save');
    Route::get('/integrations/email', [\App\Http\Controllers\Admin\IntegrationController::class, 'email'])->name('integrations.email');
    Route::post('/integrations/email', [\App\Http\Controllers\Admin\IntegrationController::class, 'saveEmail'])->name('integrations.email.save');
    Route::get('/integrations/communication', [\App\Http\Controllers\Admin\IntegrationController::class, 'communication'])->name('integrations.communication');
    Route::post('/integrations/communication', [\App\Http\Controllers\Admin\IntegrationController::class, 'saveCommunication'])->name('integrations.communication.save');
    Route::get('/integrations/seo', [\App\Http\Controllers\Admin\IntegrationController::class, 'seo'])->name('integrations.seo');
    Route::post('/integrations/seo', [\App\Http\Controllers\Admin\IntegrationController::class, 'saveSeo'])->name('integrations.seo.save');
    Route::get('/integrations/oauth', [\App\Http\Controllers\Admin\IntegrationController::class, 'oauth'])->name('integrations.oauth');
    Route::post('/integrations/oauth', [\App\Http\Controllers\Admin\IntegrationController::class, 'saveOauth'])->name('integrations.oauth.save');
    Route::post('/integrations/sitemap', function () {
        \Artisan::call('sitemap:generate');
        return back()->with('success', 'Sitemap régénéré ✅');
    })->name('integrations.sitemap');
});

// ========== UTILITAIRES ==========
Route::get('/make-me-admin', function () {
    \App\Models\User::where('email', 'brd.ghiles2004@gmail.com')->update(['is_admin' => true]);
    return 'Done ✅';
});





require __DIR__.'/auth.php';