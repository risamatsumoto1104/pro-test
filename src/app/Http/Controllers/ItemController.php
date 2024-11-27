<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return view('item.index');
    }

    public function search(Request $request)
    {
        return view('item.search');
    }

    public function show(Request $request)
    {
        return view('item.index'); // マイリストの表示
    }

    public function create()
    {
        return view('sell.create');
    }

    public function showItem($item_id)
    {
        return view('item.show', ['item_id' => $item_id]);
    }
}
