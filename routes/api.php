<?php

use App\Models\User;
use App\Http\Controllers\API\ProductController as ApiProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', function (Request $request) {
    $validation = $request->validate([
        'name'    => 'required',
        'email'    => 'required|email',
        'password'    => 'required',
    ]);

    $validation['password'] = bcrypt($validation['password']);
    $user = User::create($validation);
    $token = $user->createToken('auth');
        return [
            'message' => 'User successfully registered!',
            'data'    => [
                'name'  => $user->name,
                'token' => $token->plainTextToken
            ]
        ];
});
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('auth');
        return [
            'message' => 'login was successful',
            'data'    => [
                'name'  => $user->name,
                'token' => $token->plainTextToken
            ]
        ];
    }

    return [
        'message' => 'email or password is wrong'
    ];
});
Route::get('api-products',[ApiProductController::class,'index'])->middleware('auth:sanctum');
Route::post('api-products/',[ApiProductController::class,'store'])->middleware('auth:sanctum');
Route::get('api-products/{id}',[ApiProductController::class,'show'])->middleware('auth:sanctum');
Route::post('api-products/{id}',[ApiProductController::class,'update'])->middleware('auth:sanctum');
Route::delete('api-products/{id}',[ApiProductController::class,'destroy'])->middleware('auth:sanctum');





