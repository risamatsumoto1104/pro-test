<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Address;
use App\Models\Purchase;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Exception;

class PurchaseController extends Controller
{
    // 購入前の確認
    public function confirm($item_id)
    {
        // 商品情報を取得
        $item = Item::findOrFail($item_id);

        // ユーザーの住所情報を取得（ログインユーザーを想定）
        $user = auth()->user();
        $address = Address::where('user_id', $user->user_id)->first();

        // 必要な情報をビューに渡す
        return view('purchase.confirm', [
            'item' => $item,
            'address' => $address ?? (object) [
                'id' => '',
                'postal_code' => '',
                'address' => '',
                'building' => ''
            ]
        ]);
    }

    // 購入処理
    public function store(PurchaseRequest $request, $item_id)
    {
        try {
            // ログインしているユーザー情報を取得
            $user = auth()->user();

            // 商品情報と住所情報を取得
            $item = Item::findOrFail($item_id);
            $address = Address::where('user_id', $user->user_id)->first();

            // 同じ商品が既に購入されているか確認
            $existingPurchase = Purchase::where('item_id', $item_id)->first();
            if ($existingPurchase) {
                return redirect()->back()->withErrors(['error' => 'この商品は既に購入されています。']);
            }

            // 支払い方法の設定
            $paymentMethod = $request->input('payment_method');
            $paymentMethodType = $paymentMethod === 'カード支払い' ? ['card'] : ['konbini'];

            // 支払い方法をセッションに保存
            session(['payment_method' => $paymentMethod]);

            // Stripeの決済セッションを作成
            $session = Session::create([
                'payment_method_types' => $paymentMethodType,
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->item_name,
                        ],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('purchase.success', ['item_id' => $item_id]),
                'cancel_url' => route('item.purchase', ['item_id' => $item_id]),
            ]);

            // Stripeの決済ページに遷移
            return redirect($session->url);
        } catch (Exception $e) {
            // エラーが発生した場合は、前のページに戻る。
            return redirect()->back()->withErrors(['error' => '決済処理中にエラーが発生しました。: ' . $e->getMessage()]);
        }
    }

    public function success(Request $request, $item_id)
    {
        try {
            // セッションから支払い方法を取得
            $paymentMethod = session('payment_method');

            // ログインしているユーザー情報と住所情報を再度取得
            $user = auth()->user();
            $address = Address::where('user_id', $user->user_id)->first();

            // 商品が既に購入されているか確認
            if (Purchase::where('item_id', $item_id)->exists()) {
                return redirect()->route('item.purchase', ['item_id' => $item_id])
                    ->withErrors(['error' => __('この商品は既に購入済みです。')]);
            }

            // 購入が成功した際に、Purchaseテーブルに購入情報を保存
            $purchase = new Purchase();
            $purchase->buyer_user_id = $user->user_id;
            $purchase->item_id = $item_id;
            $purchase->address_id = $address->address_id;
            $purchase->payment_method = $paymentMethod;
            $purchase->save();

            // 成功ページへのリダイレクト
            return view('purchase.success', ['item' => $item_id]);
        } catch (\Exception $e) {
            return redirect()->route('item.purchase', ['item_id' => $item_id])
                ->withErrors(['error' => __('購入情報の登録中にエラーが発生しました。')]);
        }
    }
}
