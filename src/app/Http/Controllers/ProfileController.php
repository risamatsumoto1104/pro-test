<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;


class ProfileController extends Controller
{
    // プロフィール画面の表示
    public function show(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;

        // 出品した商品一覧
        $soldItems = Item::where('seller_user_id', $user->user_id)->get();

        // 購入した商品一覧
        $boughtItems = Purchase::where('buyer_user_id', $user->user_id)
            ->join('items', 'purchases.item_id', '=', 'items.item_id')
            ->get(['items.*']);

        // 購入した商品について「売り切れ(sold)」状態を判定
        foreach ($boughtItems as $item) {
            $isSold = Purchase::where('item_id', $item->item_id)->exists();
            if ($isSold) {
                $item->status = 'sold'; // 売り切れ状態に設定
            }
        }

        // タブの状態
        $tab = $request->get('tab', 'sell');

        return view('mypage.profile.show', compact('user', 'profile', 'soldItems', 'boughtItems', 'tab'));
    }

    // プロフィール設定画面を表示
    public function edit(Request $request)
    {
        // ログイン中のユーザーを取得
        $user = Auth::user();
        // ユーザーに関連するプロフィール情報を取得
        $profile = $user->profile;

        return view('mypage.profile.edit', compact('user', 'profile'));
    }

    public function update(ProfileRequest $profileRequest, AddressRequest $addressRequest)
    {
        $user = auth()->user();

        // バリデーション済みデータ取得
        $profileValidated = $profileRequest->validated();
        $addressValidated = $addressRequest->validated();

        // 画像処理
        if ($profileRequest->hasFile('profile_image')) {
            $imagePath = $profileRequest->file('profile_image')->store('public');

            // 古い画像を削除
            if ($user->profile && $user->profile->profile_image) {
                Storage::disk('public')->delete($user->profile->profile_image);
            }

            $profileValidated['profile_image'] = basename($imagePath);
        }

        // ユーザー名更新（usersテーブルのnameカラムを更新）
        if (isset($addressValidated['name'])) {
            $user->update(['name' => $addressValidated['name']]); // ユーザー名を更新
            unset($addressValidated['name']);
        }

        // プロフィール更新または作成
        $addressValidated['profile_image'] = $profileValidated['profile_image'] ?? null; // 画像も保存
        if ($user->profile) {
            $user->profile()->update($addressValidated);
        } else {
            $addressValidated['user_id'] = $user->user_id;
            $user->profile()->create($addressValidated);
        }

        // **Addressテーブルに保存または更新**
        Address::updateOrCreate(
            ['user_id' => $user->user_id], // `user_id` で検索
            [
                'postal_code' => $addressValidated['postal_code'] ?? null,
                'address' => $addressValidated['address'] ?? null,
                'building' => $addressValidated['building'] ?? null,
            ]
        );

        // 更新完了とともにリダイレクト
        return redirect()->route('home');
    }
}
