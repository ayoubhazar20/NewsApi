<?php

use App\Http\Controllers\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});















Route::middleware('auth:sanctum')->group(function () {

    // Route pour récupérer toutes les nouvelles
    Route::get('/news', [NewsController::class, 'index']);

    // Route pour créer une nouvelle
    Route::post('/news/post', [NewsController::class, 'store']);

    // Route pour mettre à jour une nouvelle existante
    Route::put('/news/{id}', [NewsController::class, 'update']);

    // Route pour supprimer une nouvelle
    Route::delete('/news/{id}', [NewsController::class, 'destroy']);

    //Recher de nouvelle par category id
    Route::get('/Category/{id}', [NewsController::class, 'searchByCategory']);

    //Recher de nouvelle par category name
    Route::get('/Category/name/{name}', [NewsController::class, 'searchByCategoryName']);
});























Route::get('/news/{id}', [NewsController::class, 'show']);
Route::get('/latestNews', [NewsController::class, 'latestNews']);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $token = $user->createToken('active')->plainTextToken;

    return response()->json(['token' => $token]);
});
Route::post('/addUser', [NewsController::class, 'addUser']);
