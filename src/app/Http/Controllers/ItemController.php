<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // 商品一覧画面を表示
    public function index()
    {
        return view('item.index');
    }

    // 商品一覧ベージの検索
    public function search(Request $request)
    {
        return view('item.search');
    }

    // マイリストの表示
    public function show(Request $request)
    {
        return view('item.index');
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
