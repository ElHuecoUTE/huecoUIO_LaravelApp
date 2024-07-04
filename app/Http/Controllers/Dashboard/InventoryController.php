<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\RegistroInventario;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InventoryController extends Controller
{
  public function create(Request $request): Response
  {
    return Inertia::render('Dashboard/Inventory/Index', [
      'status' => session('status'),
      'inventory' => Inventario::with(['estadoInventario', 'producto.TipoProducto'])->get()
    ]);
  }

  public function store(Request $request): Response
  {
    $request->validate([
      'id' => 'required|Integer',
      'stock' => 'required|Integer',
    ]);

    // Add the new stock to the current stock
    Inventario::where('inv_id', $request->id)->increment('inv_stock', $request->stock);

    return Inertia::render('Dashboard/Inventory/Index', [
      'status' => session('status'),
    ]);
  }
}
