<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboard\ClientsController;
use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\Dashboard\InventoryController;
use App\Http\Controllers\Dashboard\OrdersController;
use App\Http\Controllers\Dashboard\ProductsTypesController;
use App\Http\Controllers\Dashboard\ClientSearch;
use App\Http\Controllers\Dashboard\ProductsSearchController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\SalesController;
use App\Http\Controllers\Dashboard\SalesSearchController;
use App\Http\Controllers\Dashboard\ReportsController;
use App\Http\Controllers\Dashboard\InvoiceController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->middleware('guest')->name('login');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/dashboard/productos', [ProductsController::class, 'create'])->name('productos');
  Route::post('/dashboard/productos', [ProductsController::class, 'store'])->name('productos.store');
  Route::put('/dashboard/productos', [ProductsController::class, 'update'])->name('productos.update');
  Route::delete('/dashboard/productos/{id}', [ProductsController::class, 'destroy'])->name('productos.destroy');
  Route::get('/dashboard/productos/{id}', [ProductsController::class, 'show'])->name('productos.show');
  Route::delete('/dashboard/productos', [ProductsController::class, 'all'])->name('productos.destroy.all');

  Route::get('/dashboard/tipos-productos', [ProductsTypesController::class, 'create'])->name('tipos-productos');
  Route::post('/dashboard/tipos-productos', [ProductsTypesController::class, 'store'])->name('tipos-productos.store');
  Route::put('/dashboard/tipos-productos', [ProductsTypesController::class, 'update'])->name('tipos-productos.update');
  Route::delete('/dashboard/tipos-productos/{id}', [ProductsTypesController::class, 'destroy'])->name('tipos-productos.destroy');
  Route::get('/dashboard/tipos-productos/{id}', [ProductsTypesController::class, 'show'])->name('tipos-productos.show');


  Route::get('/dashboard/inventario', [InventoryController::class, 'create'])->name('inventario');
  Route::post('/dashboard/inventario', [InventoryController::class, 'store'])->name('inventario.store');

  Route::get('/dashboard/clientes', [ClientsController::class, 'create'])->name('clientes');
  Route::post('/dashboard/clientes', [ClientsController::class, 'store'])->name('clientes.store');
  Route::put('/dashboard/clientes', [ClientsController::class, 'update'])->name('clientes.update');
  Route::delete('/dashboard/clientes/{id}', [ClientsController::class, 'destroy'])->name('clientes.destroy');
  Route::get('/dashboard/clientes/{id}', [ClientsController::class, 'show'])->name('clientes.show');

  Route::get('/dashboard/pedidos', [OrdersController::class, 'create'])->name('pedidos');
  Route::post('/dashboard/pedidos', [OrdersController::class, 'store'])->name('pedidos.store');
  Route::put('/dashboard/pedidos', [OrdersController::class, 'update'])->name('pedidos.update');

  Route::get('/dashboard/ventas', [SalesController::class, 'create'])->name('ventas');
  Route::post('/dashboard/ventas', [SalesController::class, 'store'])->name('ventas.store');

  Route::get('/dashboard/reportes', [ReportsController::class, 'create'])->name('reportes');

  Route::get('/dashboard/facturas', [InvoiceController::class, 'create'])->name('facturas');
  Route::get('/dashboard/facturas/pdf', [InvoiceController::class, 'generate'])->name('facturas.generate');

  Route::get('/dashboard/buscar-cliente/{id}', [ClientSearch::class, 'show'])->name('buscar-cliente');
  Route::get('/dashboard/buscar-producto/{id}', [ProductsSearchController::class, 'show'])->name('buscar-producto');
  Route::get('/dashboard/buscar-venta/{id}', [SalesSearchController::class, 'show'])->name('buscar-venta');
});

require __DIR__.'/auth.php';
