<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DemoController;
use App\Http\Controllers\QrGeneratorController;
use App\Http\Controllers\PhotoUploadController;
use App\Http\Controllers\Api\DemoApiController;
use App\Http\Controllers\Api\FalAiController;

use App\Models\User;
use App\Models\Emotion;
use App\Models\Qrcode;
use App\Models\File;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Demo API（供前端 test.blade.php 和 demo.blade.php 使用）
Route::post('/demo', [DemoApiController::class, 'handle'])->name('api.demo');

// 照片上傳（Step2：真實檔案上傳）
Route::post('upload-photo', [PhotoUploadController::class, 'uploadPhoto'])->name('api.upload.photo');

// 產生 QR（正式 API）
Route::post('generate-qr', [QrGeneratorController::class, 'generate'])->name('api.qr.generate');
Route::post('generate-profile-qr', [QrGeneratorController::class, 'generateUserProfile'])->name('api.qr.profile');

// fal.ai 圖片生成 API
Route::prefix('fal')->name('api.fal.')->group(function () {
    // 測試連接
    Route::get('test', [FalAiController::class, 'test'])->name('test');

    // 列出可用模型
    Route::get('models', [FalAiController::class, 'listModels'])->name('models');

    // 一般風格圖片生成
    Route::post('generate-style-image', [FalAiController::class, 'generateStyleImage'])->name('style');

    // 基於角色檔案生成圖片
    Route::post('generate-character-image', [FalAiController::class, 'generateCharacterImage'])->name('character');
});

// 測試/除錯（GET，方便直接在瀏覽器驗證）
Route::get('test-qr', function () {
    return response()->json([
        'message' => 'QR API is working',
        'test_qr' => 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode('API測試成功'),
        'instructions' => 'POST /api/generate-qr with {"user_id":1,"content":"...","size":200}',
    ]);
})->name('api.qr.test');

Route::get('generate-qr-simple', function (Request $request) {
    $content = $request->input('content', '預設測試內容');
    $size    = max(100, min((int) $request->input('size', 200), 500));
    $qrUrl   = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data=" . urlencode($content);
    return response()->json(['success' => true, 'qr_url' => $qrUrl]);
})->name('api.qr.simple');

// 後台清單（admin.html 使用，最小可用）
Route::get('users',    fn() => User::latest('id')->limit(200)->get())->name('api.users');
Route::get('emotions', fn() => Emotion::latest('id')->limit(200)->get())->name('api.emotions');
Route::get('qrcodes',  fn() => Qrcode::latest('id')->limit(200)->get())->name('api.qrcodes');
Route::get('files',    fn() => File::latest('id')->limit(200)->get())->name('api.files');
