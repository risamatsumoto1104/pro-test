<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    // 商品一覧画面を表示
    public function index(Request $request)
    {
        // ログインしているユーザーを取得
        $user = Auth::user();

        // 'recommend'がデフォルト
        $tab = $request->get('tab', 'recommend');

        // 商品一覧を取得
        $items = Item::when($user, function ($query) use ($user) {
            // ログインしている場合、自分の出品した商品を除外
            $query->where('user_id', '!=', $user->user_id);
        })
            ->when($tab == 'sold', function ($query) {
                // "Sold"タブの場合、購入済みの商品も含む処理を追加
                $query->where('status', '!=', 'sold');
            })
            ->get();

        // 商品に対する「いいね」や購入済み状態などを設定
        $items->map(function ($item) use ($user) {
            // 購入済み商品は "Sold" と表示
            if ($item->status == 'sold') {
                $item->status_label = 'Sold';
            } else {
                $item->status_label = null;
            }

            // ログインしているユーザーが「いいね」したかどうかを判定
            if ($user) {
                $item->is_liked = $item->likes()->where('user_id', $user->id)->exists();
            }

            return $item;
        });

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
