<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Iva;
use App\Models\Pedido;
use App\Models\Ventas;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SalesController extends Controller
{
  public function create(Request $request): Response
  {
    return Inertia::render('Dashboard/Sales/Index', [
      'status' => session('status'),
      'data' => Ventas::all()
    ]);
  }

  public function store(Request $request): Response
  {
    $request->validate([
      'pedidoId' => 'required|integer',
    ]);

    $pedido = Pedido::findOrFail($request->pedidoId);
    $pedido->update([
      'fk_est_ped_id' => $pedido->fk_est_ped_id + 1
    ]);

    $iva = Iva::first();

    // Create a new sale
    $venta = Ventas::create([
      'fk_cli_id' => $pedido->fk_cli_id,
      'fk_ped_id' => $pedido->ped_id,
      'ven_tot' => $pedido->ped_tot + ($pedido->ped_tot * $iva->iva_val / 100)
    ]);
    return Inertia::render('Dashboard/Sales/Index', [
      'status' => session('status'),
      'result' => $venta::with(['pedido', 'cliente', 'pedido.detallePedido.producto.tipoProducto'])->where('ven_id', $venta->ven_id)->first()
    ]);
  }
}
