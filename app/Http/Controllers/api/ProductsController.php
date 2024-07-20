<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\EstadoProducto;
use App\Models\Producto;
use App\Models\TipoProducto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{

  /*
    Documentation by Dante
    json example
    show:
    Url: http://localhost:8000/api/productos/{id}

    store:
    Url: http://localhost:8000/api/productos
    {
      "nombre": "string",
      "valor": "numeric",
      "tipo": "integer"
    }
    update:
    Url: http://localhost:8000/api/productos/{id}
    {
      "nombre": "string",
      "valor": "numeric",
      "tipo": "integer"
    }
    destroy:
    Url: http://localhost:8000/api/productos/{id}
  */
  public function index(Request $request): JsonResponse
  {
    try {
      return $this->sendResponse(Producto::with(['tipoProducto', 'estadoProducto'])->get(), 'Productos recuperados correctamente');
    } catch (\Exception $e) {
      return $this->sendError('Error al recuperar los productos', $e->getMessage(), 400);
    }
  }

  public function store(Request $request): JsonResponse
  {
    try{
      $request->validate([
        'nombre' => 'required|string|max:30',
        'valor' => 'required|numeric|min:0',
        'tipo' => 'required|integer|min:1',
      ]);

      $details = [
        'pro_nom' => $request->nombre,
        'pro_val' => $request->valor,
        'fk_tip_pro_id' => $request->tipo
      ];

      $result = Producto::create($details);

      return $this->sendResponse($result, 'Producto creado correctamente');
    }catch (\Exception $e){
      return $this->sendError('Error al crear el producto', $e->getMessage(), 400);
    }
  }

  public function show($id): JsonResponse
  {
    try{
      return $this->sendResponse(Producto::findOrFail($id), 'Producto recuperado correctamente');
    } catch (\Exception $e){
      return $this->sendError('Error al encontrar el producto', $e->getMessage(), 400);
    }
  }

  public function update($id, Request $request): JsonResponse
  {
    try{
      $request->validate([
        'nombre' => 'required|string|max:30',
        'valor' => 'required|numeric|min:0',
        'tipo' => 'required|integer',
      ]);
      $details = [
        'pro_nom' => $request->nombre,
        'pro_val' => $request->valor,
        'fk_tip_pro_id' => $request->tipo
      ];

      $product = Producto::findOrFail($id);
      $product->update($details);


      return $this->sendResponse($product->with(['tipoProducto', 'estadoProducto'])->findOrfail($id), 'Producto actualizado correctamente');
    } catch (\Exception $e){
      return $this->sendError('Error al actualizar el producto', $e->getMessage(), 400);
    }
  }

  public function destroy($id): JsonResponse
  {
    try {
      $product = Producto::findOrFail($id);
      $product->delete();
      return $this->sendResponse($product, 'Producto eliminado correctamente');
    } catch (\Exception $e) {
      return $this->sendError('Error al eliminar el producto', $e->getMessage(), 400);
    }
  }
}
