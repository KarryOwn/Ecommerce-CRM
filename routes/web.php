<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CustomerSegmentationController;
use App\Http\Controllers\CustomerInteractionController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\CustomerSegmentController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\LoyaltyTierController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('crm.dashboard');
    })->name('dashboard');

    // CRM Routes
    Route::get('/crm/dashboard', [CrmController::class, 'dashboard'])->name('crm.dashboard');
    
    // Customer Routes
    Route::get('/crm/customers', [CrmController::class, 'customersList'])->name('customers.index');
    Route::get('/crm/customers/create', [CrmController::class, 'create'])->name('customers.create');
    Route::post('/crm/customers', [CrmController::class, 'store'])->name('customers.store');
    Route::get('/crm/customers/{customer}/edit', [CrmController::class, 'edit'])->name('customers.edit');
    Route::put('/crm/customers/{customer}', [CrmController::class, 'update'])->name('customers.update');
    Route::get('/crm/customers/{customer}', [CrmController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/loyalty', [CrmController::class, 'loyalty'])->name('customers.loyalty');
    
    // Analytics Routes
    Route::get('/crm/analytics', [AnalyticsController::class, 'dashboard'])->name('analytics.dashboard');
    Route::get('/analytics', [AnalyticsController::class, 'dashboard'])->name('analytics.dashboard');
    
    // Segmentation Routes
    Route::prefix('crm')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
        Route::get('/segmentation', [CustomerSegmentationController::class, 'index'])->name('segmentation.index');
        Route::get('/segmentation/create', [CustomerSegmentationController::class, 'create'])->name('segmentation.create');
        Route::post('/segmentation', [CustomerSegmentationController::class, 'store'])->name('segmentation.store');
        Route::get('/segmentation/{segment}/edit', [CustomerSegmentationController::class, 'edit'])->name('segmentation.edit');
        Route::put('/segmentation/{segment}', [CustomerSegmentationController::class, 'update'])->name('segmentation.update');
        Route::delete('/segmentation/{segment}', [CustomerSegmentationController::class, 'destroy'])->name('segmentation.destroy');
        Route::post('/segmentation/{segment}/evaluate', [CustomerSegmentationController::class, 'evaluateSegment'])
            ->name('segmentation.evaluate');
        Route::post('/segments/evaluate-all', [CustomerSegmentationController::class, 'evaluateAll'])
            ->name('segments.evaluate-all');
        Route::get('/segmentation/{segment}', [CustomerSegmentationController::class, 'show'])
            ->name('segments.show');
        
        // Customer Interaction Routes
        Route::get('/customers/{customer}/interactions', [CustomerInteractionController::class, 'index'])
            ->name('customers.interactions.index');
        Route::get('/customers/{customer}/interactions/create', [CustomerInteractionController::class, 'create'])
            ->name('customers.interactions.create');
        Route::post('/customers/{customer}/interactions', [CustomerInteractionController::class, 'store'])
            ->name('customers.interactions.store');
        Route::get('/customers/{customer}/interactions/{interaction}', [CustomerInteractionController::class, 'show'])
            ->name('customers.interactions.show');
        Route::put('/customers/{customer}/interactions/{interaction}', [CustomerInteractionController::class, 'update'])
            ->name('customers.interactions.update');
        Route::get('/customers/{customer}/interactions/{interaction}/attachments/{index}', [CustomerInteractionController::class, 'downloadAttachment'])
            ->name('customers.interactions.download');
        Route::patch('/customers/{customer}/interactions/{interaction}/resolve', 
            [CustomerInteractionController::class, 'resolve'])
            ->name('customers.interactions.resolve');
        
        // General Interactions Dashboard
        Route::get('/interactions', [CustomerInteractionController::class, 'dashboard'])
            ->name('interactions.dashboard');
        Route::get('/interactions/create', [CustomerInteractionController::class, 'create'])
            ->name('interactions.create');
        Route::post('/interactions', [CustomerInteractionController::class, 'store'])
            ->name('interactions.store');
        Route::get('/interactions/{interaction}', [CustomerInteractionController::class, 'show'])
            ->name('interactions.show');
        
        // Product Routes
        Route::resource('products', ProductsController::class);
        Route::post('products/{product}/update-stock', [ProductsController::class, 'updateStock'])
            ->name('products.update-stock');
        
        // Order Routes
        Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
        Route::get('/orders/create', [OrdersController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrdersController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}', [OrdersController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [OrdersController::class, 'updateStatus'])->name('orders.update-status');
        
        // Campaign Routes
        Route::resource('campaigns', CampaignController::class);
        Route::post('/campaigns/{campaign}/execute', [CampaignController::class, 'execute'])
            ->name('campaigns.execute');
        
        // Loyalty Routes
        Route::get('/loyalty', [LoyaltyController::class, 'dashboard'])
            ->name('loyalty.dashboard');
        Route::get('/loyalty/customer/{customer}', [LoyaltyController::class, 'customerPoints'])
            ->name('loyalty.customer-points');
        Route::get('/loyalty/dashboard', [LoyaltyController::class, 'dashboard'])->name('loyalty.dashboard');
        Route::get('/loyalty/members', [LoyaltyController::class, 'members'])->name('loyalty.members');
        Route::get('/loyalty/transactions', [LoyaltyController::class, 'transactions'])->name('loyalty.transactions');
        Route::get('/loyalty/tiers', [LoyaltyController::class, 'tiers'])->name('loyalty.tiers');
        Route::resource('loyalty/tiers', LoyaltyTierController::class);

        Route::get('/loyalty/transactions/{transaction}', [LoyaltyController::class, 'showTransaction'])->name('loyalty.transactions.show');
    });
});
