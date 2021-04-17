<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:sanctum'], function() {
  Route::get('/user', function(Request $request) {
    return $request->user();
  });
  
  Route::get("/ads", [\App\Http\Controllers\AdsController::class, "index"]);
  Route::post("/ads", [\App\Http\Controllers\AdsController::class, "store"]);
});

Route::post('/login', function(Request $request) {
  $request->validate([
    'username' => 'required|string',
    'password' => 'required'
  ]);

  $user = \App\Models\User::where('username', $request->username)->first();
  if(!$user) return response()->json(['error' => true, 'message' => 'Username tidak terdaftar'], 404);

  $checkPassword = Hash::check($request->password, $user->password);
  if(!$checkPassword) return response()->json(['error' => true, 'message' => 'Password tidak cocok'], 400);

  $token = $user->createToken($request->username);
  return response()->json(['token' => $token->plainTextToken], 201);
});

Route::get("/video", [\App\Http\Controllers\VideoController::class, "index"]);
Route::get("/video/stream", [\App\Http\Controllers\VideoController::class, "streamVideo"]);
Route::get("/video/download", [\App\Http\Controllers\VideoController::class, "downloadVideo"]);

Route::get("/video/{id}/detail", [\App\Http\Controllers\VideoController::class, "show"]);
Route::post("/video", [\App\Http\Controllers\VideoController::class, "store"]);

Route::get("/ads/active", [\App\Http\Controllers\AdsController::class, "getActiveAds"]);
