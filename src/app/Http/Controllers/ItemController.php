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
        // 検索結果を取得
        $searchResults = Item::KeywordSearch($request->keyword)->get();

        // タブが設定されていない場合、デフォルトで'recommend'を使用
        $tab = $request->query('tab', 'recommend');

        // ビューに検索結果とタブを渡す
        return view('item.index', compact('searchResults', 'tab'));
    }


    // 商品詳細の表示
    public function showItem($item_id)
    {
        // 商品データとその関連データを一度に取得するために、リレーションを読み込み
        $item = Item::with([
            'categories',
            'comments.userProfile',
            'itemLikes'
        ])
            // likes（いいね）とcomments（コメント）の数を計算して取得
            ->withCount(['itemLikes', 'comments'])
            // 指定されたIDに対応する商品データをデータベースから取得
            ->findOrFail($item_id);

        // ログインしている場合に「いいね」済みか確認
        $liked = false;
        if (Auth::check()) {
            $liked = $item->itemLikes->where('user_id', Auth::id())->isNotEmpty();
        }

        // 取得したデータ（$item, $liked）をビューに渡す
        return view('item.show', compact('item', 'liked'));
    }

    // いいね機能
    public function toggleLike($item_id)
    {
        // ログインユーザーがいるかいないかを判定
        $user = Auth::user();

        // 商品が存在するかチェック
        $item = Item::findOrFail($item_id);

        // 既にいいねがあるか確認
        $like = Like::where('item_id', $item_id)
            ->where('user_id', $user ? $user->user_id : null) // ログインしている場合、user_idを判定
            ->first();

        // いいねが存在しない場合は新規作成
        if (!$like) {
            Like::create([
                'item_id' => $item_id,
                'user_id' => $user ? $user->user_id : null, // 未認証の場合、user_idはnull
            ]);
            $liked = true;
        } else {
            // いいねがある場合は削除
            $like->delete();
            $liked = false;
        }

        // 商品のいいね数を再取得
        $likeCount = $item->itemLikes()->count();

        // レスポンスとして、いいねの数とアイコンの状態を返す
        return response()->json([
            'likeCount' => $likeCount,
            'liked' => $liked,
        ]);
    }


    // 商品の詳細よりコメントの保存
    public function storeComment(CommentRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        // コメントを保存
        $item->comments()->create([
            'user_id' => auth()->user()->user_id,  // ログインユーザーのID
            'content' => $request->comment,        // コメント内容
        ]);

        // 商品詳細ページにリダイレクト
        return redirect()->route('items.show', ['item_id' => $item_id]);
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
