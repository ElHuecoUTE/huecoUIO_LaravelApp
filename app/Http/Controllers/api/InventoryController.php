<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
  /*
    Documentation by Dante
    json example
    update:
    Url: http://localhost:8000/api/inventario/{id}
    {
      "stock": "integer"
    }
    show:
    Url: http://localhost:8000/api/inventario/{id}
    */
  public function index(Request $request): JsonResponse
  {
    try {
      return $this->sendResponse(Inventario::with(['estadoInventario', 'producto.TipoProducto'])->get(), 'Inventario recuperado correctamente');
    } catch (\Exception $e) {
      return $this->sendError('Error al recuperar el inventario', $e->getMessage(), 400);
    }
  }

  public function show($id): JsonResponse
  {
    try {
      return $this->sendResponse(Inventario::with(['estadoInventario', 'producto.TipoProducto'])->findOrFail($id), 'Inventario recuperado correctamente');
    } catch (\Exception $e) {
      return $this->sendError('Error al encontrar el inventario', $e->getMessage(), 400);
    }
  }

  public function update(Request $request, $id) : JsonResponse
  {
    try {
      $request->validate([
        'stock' => 'required|Integer',
      ]);

      $result = Inventario::where('inv_id', $id)->increment('inv_stock', $request->stock);

      return $this->sendResponse(Inventario::with(['estadoInventario', 'producto.TipoProducto'])->findOrFail($id), 'Inventario actualizado correctamente');
    } catch (\Exception $e) {
      return $this->sendError('Error al actualizar el inventario', $e->getMessage(), 400);
    }
  }
}
