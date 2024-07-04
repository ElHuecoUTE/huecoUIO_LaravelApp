<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\EstadoPedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class OrdersController extends Controller
{
  /*
    Documentation by Dante
    json example
    show:
    Url: http://localhost:8000/api/pedidos/{id}

    store:
    Url: http://localhost:8000/api/pedidos
    {
      "clienteId": "integer",
      "detallePedido": [
        {
          "productoId": "integer",
          "cantidad": "integer"
        }
      ]
    }
    update:
    Url: http://localhost:8000/api/pedidos/{id}
    {
      "estado": "integer"
    }
    destroy:
    Url: http://localhost:8000/api/pedidos/{id}
    */
  public function index(): JsonResponse
  {
    try {
      return $this->sendResponse(Pedido::with(['cliente', 'estadoPedido', 'detallePedido.producto.tipoProducto'])->get(), 'Pedidos recuperados correctamente');
    } catch (\Exception $e) {
      return $this->sendError('Error al recuperar los pedidos', $e->getMessage(), 400);
    }
  }

  public function show($id): JsonResponse
  {
    try {
      return $this->sendResponse(Pedido::with(['cliente', 'estadoPedido', 'detallePedido.producto.tipoProducto'])->findOrFail($id), 'Pedido recuperado correctamente');
    } catch (\Exception $e) {
      return $this->sendError('Error al encontrar el pedido', $e->getMessage(), 400);
    }
  }

  public function store(Request $request): JsonResponse
  {
    try {
      $request->validate([
        'clienteId' => 'required|integer',
        'detallePedido' => 'required|array',
        'detallePedido.*.productoId' => 'required|integer',
        'detallePedido.*.cantidad' => 'required|integer',
      ]);

      $pedido = Pedido::create([
        'fk_cli_id' => $request->clienteId,
        'ped_tot' => 0,
        'ped_fec' => date('Y-m-d'),
      ]);

      $total = 0;
      foreach ($request->detallePedido as $detalle) {
        $producto = Producto::findOrFail($detalle['productoId']);
        $total += $producto->pro_pre * $detalle['cantidad'];
        $pedido->detallePedido()->create([
          'fk_pro_id' => $detalle['productoId'],
          'det_ped_can' => $detalle['cantidad'],
          'det_ped_pre' => $producto->pro_pre
        ]);
      }

      $pedido->update([
        'ped_tot' => $total
      ]);

      return $this->sendResponse(Pedido::with(['cliente', 'estadoPedido', 'detallePedido.producto.tipoProducto'])->findOrFail($pedido->ped_id), 'Pedido creado correctamente');
    } catch (\Exception $e) {
      return $this->sendError('Error al crear el pedido', $e->getMessage(), 400);
    }
  }

  public function update($id, Request $request): JsonResponse
  {
    try {
      $request->validate([
        'estado' => 'required|integer',
      ]);

      $pedido = Pedido::findOrFail($id);
      $pedido->update([
        'fk_est_ped_id' => $request->estado
      ]);

      return $this->sendResponse(Pedido::with(['cliente', 'estadoPedido', 'detallePedido.producto.tipoProducto'])->findOrFail($pedido->ped_id), 'Pedido actualizado correctamente');
    } catch (\Exception $e) {
      return $this->sendError('Error al actualizar el pedido', $e->getMessage(), 400);
    }
  }

  public function destroy($id): JsonResponse
  {
    try {
      $pedido = Pedido::findOrFail($id);
      $pedido->update([
        'fk_est_ped_id' => 4
      ]);
      return $this->sendResponse(null, 'Pedido cancelado correctamente');
    } catch (\Exception $e) {
      return $this->sendError('Error al eliminar el pedido', $e->getMessage(), 400);
    }
  }
}
