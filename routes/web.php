<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return redirect('canvas-ui');
});

Route::get('/test', function (\Symfony\Component\HttpFoundation\Request $req) {
    $data = $req->header();
    $other = [$req->getClientIp()];
    return compact('data', 'other');
});

Route::prefix('canvas-ui')->group(function () {
    Route::prefix('api')->group(function () {
        Route::get('posts', [\App\Http\Controllers\CanvasUiController::class, 'getPosts']);
        Route::get('posts/{slug}', [\App\Http\Controllers\CanvasUiController::class, 'showPost'])
             ->middleware('Canvas\Http\Middleware\Session');

        Route::get('tags', [\App\Http\Controllers\CanvasUiController::class, 'getTags']);
        Route::get('tags/{slug}', [\App\Http\Controllers\CanvasUiController::class, 'showTag']);
        Route::get('tags/{slug}/posts', [\App\Http\Controllers\CanvasUiController::class, 'getPostsForTag']);

        Route::get('topics', [\App\Http\Controllers\CanvasUiController::class, 'getTopics']);
        Route::get('topics/{slug}', [\App\Http\Controllers\CanvasUiController::class, 'showTopic']);
        Route::get('topics/{slug}/posts', [\App\Http\Controllers\CanvasUiController::class, 'getPostsForTopic']);

        Route::get('users/{id}', [\App\Http\Controllers\CanvasUiController::class, 'showUser']);
        Route::get('users/{id}/posts', [\App\Http\Controllers\CanvasUiController::class, 'getPostsForUser']);
    });

    Route::get('/{view?}', [\App\Http\Controllers\CanvasUiController::class, 'index'])
         ->where('view', '(.*)')
         ->name('canvas-ui');
});





Route::get('/safeTickect', function () {
    $total  = Redis::get('safe:count');//获取奖池数
    return view('safe-ticket', compact('total'));
});


Route::post('/safeTickect',  [\App\Http\Controllers\Controller::class, 'safeTickect']
);

// Route::post('/safeTickect',  function(){
//     $data = ['number'=>"123,123,123,123,123,123,123,123,123"];
//     return $data;
// }
// );



Route::get('/LiangSpi',  [\App\Http\Controllers\SpiderController::class, 'index']
);