<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TipoProducto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsTypesController extends Controller
{
  /*
    Documentation by Dante
    json example
    show:
    Url: http://localhost:8000/api/tiposproductos/{id}

    store:
    Url: http://localhost:8000/api/tiposproductos
    {
      "nombre": "string"
    }
    update:
    Url: http://localhost:8000/api/tiposproductos/{id}
    {
      "nombre": "string"
    }
    destroy:
    Url: http://localhost:8000/api/tiposproductos/{id}
    */
  public function index(Request $request): JsonResponse
  {
    try {
      return $this->sendResponse(TipoProducto::all(), 'Tipos de productos recuperados correctamente');
    } catch (\Exception $e) {
      return $this->sendError('Error al recuperar los tipos de productos', $e->getMessage(), 400);
    }
  }

  public function store(Request $request): JsonResponse
  {
    try {
      $request->validate([
        'nombre' => 'required|string|max:15',
      ]);

      $result = TipoProducto::create([
        'tip_pro_nom' => $request->nombre,
      ]);

      return $this->sendResponse($result, 'Tipo de producto creado correctamente');
    } catch (\Exception $e) {
      return $this->sendError('Error al crear el tipo de producto', $e->getMessage(), 400);
    }
  }

  public function show($id): JsonResponse
  {
    try {
      return $this->sendResponse(TipoProducto::findOrFail($id), 'Tipo de producto recuperado correctamente');
    } catch (\Exception $e) {
      return $this->sendError('Error al encontrar el tipo de producto', $e->getMessage(), 400);
    }
  }

  public function update(Request $request, $id): JsonResponse
  {
    try {
      $request->validate([
        'nombre' => 'required|string|max:15',
      ]);

      $result = TipoProducto::where('tip_pro_id', $id)
        ->update([
          'tip_pro_nom' => $request->nombre,
        ]);

      return $this->sendResponse(TipoProducto::findOrFail($id), 'Tipo de producto actualizado correctamente');
    } catch (\Exception $e) {
      return $this->sendError('Error al actualizar el tipo de producto', $e->getMessage(), 400);
    }
  }

  public function destroy($id): JsonResponse
  {
    try {
      $result = TipoProducto::findOrFail($id);
      $result->delete();
      return $this->sendResponse($result, 'Tipo de producto eliminado correctamente');
    } catch (\Exception $e) {
      return $this->sendError('Error al eliminar el tipo de producto', $e->getMessage(), 400);
    }
  }
}
