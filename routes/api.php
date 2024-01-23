<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UseController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route For CRUD by API...
Route::post('add_user', [UseController::class, 'addUser']);
Route::get('get_user', [UseController::class, 'allUser']);
Route::get('get_user_indv/{id}', [UseController::class, 'indivisualUser']);
Route::put('update_user/{id}', [UseController::class, 'updateUser']);
Route::delete('delete_user/{id}', [UseController::class, 'deleteUser']);

// Route For Login..... 
Route::post('login', [UseController::class, 'userLogin']);

// Route For UnAuthentication...
Route::get('unauthentic', [UseController::class, 'unauthentic'])->name('unauthentic');

// Route by Middleware Authentication...
// Route::middleware('auth:api')->group(function(){
//     Route::get('get_user', [UseController::class, 'allUser']);
//     Route::get('get_user_indv/{id}', [UseController::class, 'indivisualUser']);
//     Route::get('logout', [UseController::class, 'logout']);
// });


