<?php

namespace App\Http\Controllers;

class MainController extends Controller
{
    public function index()
    {
        // 首頁（把你的 index.html 改為 Blade 放這）
        return view('main');
    }
}
