<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CustomerSegmentationController;
use App\Http\Controllers\CustomerInteractionController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\CustomerSegmentController;

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
    });
});
