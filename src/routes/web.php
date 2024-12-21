<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\RegisteredUserController;

// 未認証ユーザーがアクセスできるルート
// ユーザー登録
Route::post('/register', [RegisteredUserController::class, 'store'])->middleware(['guest']);
// 商品一覧画面
Route::get('/', [ItemController::class, 'index'])->name('home');
// 商品検索
Route::get('/search', [ItemController::class, 'search'])->name('item.search');
// 商品詳細画面
Route::get('/item/{item_id}', [ItemController::class, 'showItem'])->name('items.show');


// ログインユーザーのみがアクセスできるルート
Route::middleware('auth')->group(function () {
    // いいね機能
    Route::post('/item/{item_id}/like', [ItemController::class, 'toggleLike'])->name('item.toggleLike');

    // 商品詳細画面にコメントを投稿
    Route::post('/item/{item_id}', [ItemController::class, 'storeComment'])->name('item.comment');

    // 商品出品画面
    Route::get('/sell', [ItemController::class, 'create'])->name('item.sell.create');
    Route::post('/sell', [ItemController::class, 'storeItem'])->name('item.sell.store');

    // 送付先住所変更ページ
    Route::get('/purchase/address/{item_id}', [AddressController::class, 'edit'])->name('purchase.address.edit');
    Route::patch('/purchase/address/{item_id}', [AddressController::class, 'update'])->name('purchase.address.update');

    // 商品購入画面
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'confirm'])->name('item.purchase');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');

    // プロフィール関連画面
    Route::get('/mypage', [ProfileController::class, 'show'])->name('mypage');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.profile');
    Route::patch('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.profile.update');

    // ログアウト
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});
