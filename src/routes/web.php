<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProfileController;

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

// 商品一覧画面（トップ画面）
Route::get('/', [ItemController::class, 'index']);
Route::get('/search', [ItemController::class, 'search']);
Route::get('/?tab=mylist', [ItemController::class, 'show']);

// 商品詳細画面
Route::get('/item/{item_id}', [ItemController::class, 'showItem']);

// 商品購入画面
Route::get('/purchase/{item_id}', [PurchaseController::class, 'confirm']);
// Route::post('/purchase/{item_id}', [PurchaseController::class, 'confirm']);
Route::post('/purchase/{item_id}/store', [PurchaseController::class, 'store']);

// 送付先住所変更ページ
Route::get('/purchase/address/{item_id}', [AddressController::class, 'update']);
// Route::patch('/purchase/address/{item_id}', [AddressController::class, 'update']);

// 商品出品画面
Route::get('/sell', [ItemController::class, 'create']);

// プロフィール関連画面
Route::get('/mypage', [ProfileController::class, 'show']);
Route::get('/mypage/profile', [ProfileController::class, 'edit']);
// Route::patch('/mypage/profile', [ProfileController::class, 'edit']);
Route::get('/mypage?tab=buy', [ProfileController::class, 'showBoughtItems']);
Route::get('/mypage?tab=sell', [ProfileController::class, 'showSoldItems']);


// 会員登録画面 後で消す
Route::view('/register', 'auth.register');

// ログイン画面　後で消す
Route::view('/login', 'auth.login');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
