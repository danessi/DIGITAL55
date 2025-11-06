<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\VentaController;

/* 
Route::get('/tiendas', [TiendaController::class, 'index']);
Route::get('/tiendas/{id}', [TiendaController::class, 'show']);
Route::post('/tiendas', [TiendaController::class, 'store']);
Route::put('/tiendas/{id}', [TiendaController::class, 'update']);
Route::delete('/tiendas/{id}', [TiendaController::class, 'destroy']);
Route::post('/venta', [VentaController::class, 'vender']); 
*/










//just for login using sanctum

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get("/rutaACrear1", [AuthController::class, "rutaACrear1"]);
    Route::get("/rutaACrear2", [AuthController::class, "rutaACrear2"]);
});

Route::middleware('auth:sanctum')->get('/orders', [OrderController::class, 'index']);
Route::middleware('auth:sanctum')->post('/orders', [OrderController::class, 'store']);
Route::middleware('auth:sanctum')->get('/orders/{order}', [OrderController::class, 'show']);