<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    // 商品一覧画面を表示
    public function index(Request $request)
    {
        // ログインしているユーザーを取得
        $user = Auth::user();

        // 'recommend'がデフォルト
        $tab = $request->query('tab', 'recommend');

        $items = Item::query()
            ->when($tab === 'mylist', function ($query) use ($user) {
                if ($user) {
                    // ユーザーが「いいね」した商品だけを取得
                    $likedItemIds = Like::where('user_id', $user->user_id)->pluck('item_id');
                    return $query->whereIn('id', $likedItemIds);
                }
                return $query->whereRaw('1 = 0'); // 未認証の場合は空の結果
            })
            ->when($tab === 'recommend', function ($query) use ($user) {
                if ($user) {
                    // ユーザーが出品した商品を除外
                    return $query->where('seller_user_id', '!=', $user->seller_user_id);
                }
                return $query; // 未認証の場合は全商品
            })
            ->get();

        return view('item.index', compact('items', 'tab'));
    }

    // 商品一覧ベージの検索
    public function search(Request $request)
    {
        return view('item.search');
    }

    // 商品詳細の表示
    public function showItem($item_id)
    {
        return view('item.show', ['item_id' => $item_id]);
    }

    // 商品の詳細よりコメントの保存
    public function storeComment(CommentRequest $request, $item_id)
    {
        return redirect('/item/' . $item_id);
    }

    // 商品の出品ページの表示
    public function create(Request $request)
    {
        return view('sell.create');
    }

    // 出品するものを保存
    public function storeItem(ExhibitionRequest $request)
    {
        return view('sell.create');
    }
}
