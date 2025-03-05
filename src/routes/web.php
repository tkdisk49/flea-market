<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RegisterController;

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

Route::get('/', [ItemController::class, 'index'])->name('home');
Route::get('/search', [ItemController::class, 'search'])->name('items.search');

Route::get('/item/{id}', [ItemController::class, 'detail'])->name('detail');

// Thanksページの表示
Route::get('/purchase/complete', function () {
    return view('purchase.complete');
})->name('purchase.complete');

Route::get('/login', [AuthenticatedSessionController::class, 'show'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::middleware(['auth'])->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'show'])->name('show.edit.profile');
    Route::get('/mypage', [ProfileController::class, 'mypage'])->name('mypage');
    Route::patch('/mypage/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell/store', [ItemController::class, 'store'])->name('items.store');

    Route::post('/like/{id}', [LikeController::class, 'toggleLike'])->name('like.toggle');
    Route::post('/item/{id}/comment', [CommentController::class, 'store'])->name('comment.store');

    Route::get('/purchase/{id}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{id}/confirm', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::post('/purchase/{id}/payment_method', [PurchaseController::class, 'updatePaymentMethod'])->name('purchase.update-payment');
    Route::get('/purchase/address/{id}', [PurchaseController::class, 'changeAddress'])->name('purchase.change-address');
    Route::patch('/purchase/address/update', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');
});
