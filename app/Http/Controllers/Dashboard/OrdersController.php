<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\EstadoPedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrdersController extends Controller
{
  public function create()
  {
    return Inertia::render('Dashboard/Orders/Index', [
      'status' => session('status'),
      'orders' => Pedido::with(['cliente', 'EstadoPedido', 'detallePedido.producto', 'detallePedido.producto.tipoProducto'])->get(),
    ]);
  }

  public function store()
  {
    request()->validate([
      'clientId' => 'required|Integer',
      'productIds' => 'required|array',
      'total' => 'required|numeric',
    ]);

    $order = Pedido::create([
      'fk_cli_id' => request('clientId'),
      'ped_tot' => request('total'),
      'fk_est_ped_id' => 1,
      'ped_fec' => date('Y-m-d H:i:s'),
    ]);

    foreach (request('productIds') as $product) {
      $order->detallePedido()->create([
        'fk_pro_id' => $product['pro_id'],
        'det_ped_can' => $product['count'],
        'det_ped_pre' => $product['pro_val'],
      ]);
    }
    $result = Pedido::with(['cliente', 'EstadoPedido', 'detallePedido.producto', 'detallePedido.producto.tipoProducto'])->where('ped_id', $order->ped_id)->first();


    return Inertia::render('Dashboard/Orders/Index', [
      'status' => session('status'),
      'result' => $result,
    ]);
  }

  public function update(Request $request): Response
  {
    $request->validate([
      'id' => 'required|integer',
    ]);

    $order = Pedido::find($request->input('id'));

    if ($request->deleteOrder) {
      $order->update([
        'fk_est_ped_id' => 4,
      ]);
      $state = EstadoPedido::find(4);
      return Inertia::render('Dashboard/Orders/Index', [
        'status' => session('status'),
        'updateState' => [
          'id' => $order->ped_id,
          'state' => $state->est_ped_id,
          'stateName' => $state->est_ped_nom,
        ]
      ]);
    } else {
      if ($order->fk_est_ped_id == 4) {
        throw new \Exception('No se puede actualizar el estado de un pedido cancelado');
      }

      $newState = $order->fk_est_ped_id + 1;
      $order->update([
        'fk_est_ped_id' => $newState,
      ]);

      $state = EstadoPedido::find($order->fk_est_ped_id);

      return Inertia::render('Dashboard/Orders/Index', [
        'status' => session('status'),
        'updateState' => [
          'id' => $order->ped_id,
          'state' => $state->est_ped_id,
          'stateName' => $state->est_ped_nom,
        ]
      ]);
    }
  }
}
