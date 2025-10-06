<?php

use Illuminate\Support\Facades\Route;

// 首頁（介紹頁）
Route::view('/', 'landing')->name('landing');

// Demo 頁
Route::view('/demo', 'demo')->name('demo');

// API 測試頁
// Route::view('/workflow/test', 'workflow.test')->name('workflow.test');

// 超好用的 QR 除錯頁
Route::get('/qr-debug', function () {
    $url = url('/demo');
    $img = "https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=" . urlencode($url) . '&cb=' . time();
    return <<<HTML
<!doctype html><meta charset="utf-8">
<h3>QR Debug</h3>
<p>Payload: {$url}</p>
<img src="{$img}" alt="QR" style="border:1px solid #ddd">
HTML;
});
