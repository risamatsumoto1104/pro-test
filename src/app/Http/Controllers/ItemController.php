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

        // 商品一覧のデータの取得
        $items = Item::query()

            // タブがmylistの場合
            ->when($tab === 'mylist', function ($query) use ($user) {
                // ログイン済みユーザーの場合
                if ($user) {
                    // Likeテーブルから、ログイン中のユーザーが「いいね」した商品のitem_idをリスト形式で取得
                    $likedItemIds = Like::where('user_id', $user->user_id)->pluck('item_id');
                    // 取得したリストをwhereInで指定して、該当する商品だけをItemテーブルから取得
                    return $query->whereIn('id', $likedItemIds);
                }
                // 未認証の場合・・・空の結果を返す
                return $query->whereRaw('1 = 0');
            })

            // タブがrecommendの場合
            ->when($tab === 'recommend', function ($query) use ($user) {
                // ログイン済みユーザーの場合
                if ($user) {
                    // ユーザーが出品した商品を除外（seller_user_idがログイン中のユーザーのseller_user_idと異なる商品だけを取得）
                    return $query->where('seller_user_id', '!=', $user->seller_user_id);
                }
                // 未認証の場合・・・条件を付けずにすべての商品を取得
                return $query;
            })
            // $itemsに、条件に一致する商品が格納
            ->get();

        // ビューitem.indexに、$itemsと$tabを渡して表示
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
        // 商品データとその関連データを一度に取得するために、リレーションを読み込み
        $item = Item::with([
            'categories',
            'comments.userProfile',
            'likes'
        ])
            // likes（いいね）とcomments（コメント）の数を計算して取得
            ->withCount(['likes', 'comments'])
            // 指定されたIDに対応する商品データをデータベースから取得
            ->findOrFail($item_id);

        // 取得したデータ（$item）をitems.showビューに渡す
        return view('item.show', compact('item'));
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
