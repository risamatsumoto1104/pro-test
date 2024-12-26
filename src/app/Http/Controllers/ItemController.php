<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;
use App\Models\Category;
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
        $keyword = $request->query('keyword', ''); // 検索キーワードを取得

        // 商品一覧のデータの取得
        $items = Item::query()
            // タブがmylistの場合
            ->when($tab === 'mylist', function ($query) use ($user) {
                // ログイン済みユーザーの場合
                if ($user) {
                    $likedItemIds = Like::where('user_id', $user->user_id)->pluck('item_id');
                    return $query->whereIn('item_id', $likedItemIds);
                }
                return $query->whereRaw('1 = 0');
            })
            // タブがrecommendの場合
            ->when($tab === 'recommend', function ($query) use ($user) {
                // ログイン済みユーザーの場合
                if ($user) {
                    return $query->where('seller_user_id', '!=', $user->seller_user_id);
                }
                return $query;
            })
            // キーワード検索
            ->when($keyword, function ($query) use ($keyword) {
                return $query->where('item_name', 'like', '%' . $keyword . '%');
            })
            // $itemsに、条件に一致する商品が格納
            ->get();

        // 売り切れ(sold)状態の判定
        foreach ($items as $item) {
            // 購入されているかどうか、売り切れ状態を判定
            $isSold = Purchase::where('item_id', $item->item_id)->exists();
            if ($isSold) {
                $item->status = 'sold'; // 売り切れ状態に設定
            }
        }

        // ビューitem.indexに、$itemsと$tab、検索キーワードを渡して表示
        return view('item.index', compact('items', 'tab', 'keyword'));
    }


    // 商品一覧ベージの検索
    public function search(Request $request)
    {
        // 検索キーワードを取得
        $keyword = $request->query('keyword', ''); // キーワードがリクエストに存在しない場合は空文字

        // 検索結果を取得
        $searchResults = Item::where('item_name', 'like', '%' . $keyword . '%')->get();

        // 売り切れ(sold)状態の判定
        foreach ($searchResults as $item) {
            // 購入されているかどうか、売り切れ状態を判定
            $isSold = Purchase::where('item_id', $item->item_id)->exists();
            if ($isSold) {
                $item->status = 'sold'; // 売り切れ状態に設定
            }
        }

        // タブが設定されていない場合、デフォルトで'recommend'を使用
        $tab = $request->query('tab', 'recommend');

        // ビューに検索結果とタブ、キーワードを渡す
        return view('item.index', compact('searchResults', 'tab', 'keyword'));
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
        // 認証済みのユーザーを取得
        $user = Auth::user();

        // 商品を取得
        $item = Item::findOrFail($item_id);

        // 既にいいねしているか確認
        $like = Like::where('user_id', $user->user_id)
            ->where('item_id', $item->item_id)
            ->first();

        // いいねの追加または解除
        if ($like) {
            // いいねを解除
            $like->delete();
            $liked = false;
        } else {
            // いいねを追加
            $item->itemLikes()->create(['user_id' => $user->user_id]);
            $liked = true;
        }

        // 商品のいいね合計数を取得
        $likeCount = $item->itemLikes()->count();

        // いいねアイコンのパスを設定
        $iconPath = $liked ? '/storage/星アイコン_liked.png' : '/storage/星アイコン8.png';

        // JSONでレスポンスを返す
        return response()->json([
            'liked' => $liked,
            'likeCount' => $likeCount,
            'iconPath' => $iconPath
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
        // 認証済みのユーザーを取得
        $user = Auth::user();

        // カテゴリーの取得
        $categories = Category::all();

        return view('sell.create', compact('categories'));
    }


    // 出品するものを保存
    public function storeItem(ExhibitionRequest $request)
    {
        // ログインしているユーザー情報を取得
        $user = auth()->user();

        // 出品データをItemsテーブルに保存
        $sellItem = new Item();
        $sellItem->seller_user_id = $user->user_id; // カラム名を確認
        $sellItem->item_name = $request->input('item_name');
        $sellItem->brand_name = $request->input('brand_name') ?? null;
        $sellItem->price = $request->input('price');
        $sellItem->description = $request->input('description');
        $sellItem->condition = $request->input('condition');

        // 画像保存処理
        if ($request->hasFile('item_image')) {
            // ファイルを storage/app/public に保存
            $path = $request->file('item_image')->store('item_images', 'public');

            // パスを取得して保存（basenameでファイル名のみ取得）
            $sellItem->item_image = basename($path);
        }

        $sellItem->save();

        // 中間テーブルにカテゴリーを関連付け
        if ($request->has('categories')) {
            $sellItem->categories()->attach($request->input('categories'));
        }

        // 出品後、マイページの「出品タブ」にリダイレクト
        return redirect()->route('mypage', ['tab' => 'sell']);
    }
}
