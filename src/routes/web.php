<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthenticatedSessionController;

// 未認証ユーザーがアクセスできるルート
// 商品一覧画面
Route::get('/', [ItemController::class, 'index'])->name('home');
// 商品検索
Route::get('/search', [ItemController::class, 'search']);
// 商品詳細画面
Route::get('/item/{item_id}', [ItemController::class, 'showItem']);


// ログインユーザーのみがアクセスできるルート
Route::middleware('auth')->group(function () {
    // 商品詳細画面にコメントを投稿
    Route::post('/item/{item_id}', [ItemController::class, 'storeComment']);

    // 商品出品画面
    Route::get('/sell', [ItemController::class, 'create']);
    Route::post('/sell', [ItemController::class, 'storeItem']);

    // 商品購入画面
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'confirm']);
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store']);

    // 送付先住所変更ページ
    Route::get('/purchase/address/{item_id}', [AddressController::class, 'edit']);
    Route::patch('/purchase/address/{item_id}', [AddressController::class, 'update']);

    // プロフィール関連画面
    Route::get('/mypage', [ProfileController::class, 'show']);
    Route::patch('/mypage/profile', [ProfileController::class, 'edit']);

    // ログアウト
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});
