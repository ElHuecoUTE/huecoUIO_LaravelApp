<?php

use App\Http\Controllers\api\ClientsController;
use App\Http\Controllers\api\OrdersController;
use App\Http\Controllers\api\ProductsController;
use App\Http\Controllers\api\InventoryController;
use App\Http\Controllers\api\ProductsTypesController;
use App\Http\Controllers\api\RegisterController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
  Documentación de la API by D4NTE
  - Para hacer uso de la API se debe de tener un token de autenticación que se obtiene al registrarse o loguearse
  headers: {
    'Accept': 'application/json',
    'Authorization': 'Bearer ' + token
  }
  Esto debe estar en la sección de headers en postman

  - La url para registrar un usuario es: http://localhost:8000/api/register
  - La url para loguearse es: http://localhost:8000/api/login
*/

Route::controller(RegisterController::class)->group(function () {
  Route::post('register', 'register');
  Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group(
  function () {
    Route::apiResource('clientes', ClientsController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::apiResource('productos', ProductsController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::apiResource('inventario', InventoryController::class)->only(['index', 'show', 'update']);
    Route::apiResource('tiposproductos', ProductsTypesController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::apiResource('pedidos', OrdersController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
  });


