<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return view('items.index');
    }

    public function search(Request $request)
    {
        return view('items.search');
    }

    public function show(Request $request)
    {
        return view('items.index'); // マイリストの表示
    }

    public function create()
    {
        return view('sells.create');
    }

    public function showItem($item_id)
    {
        return view('items.show', compact('item_id'));
    }
}
