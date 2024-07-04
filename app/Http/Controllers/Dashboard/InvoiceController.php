<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Cliente;

class InvoiceController extends Controller
{
  public function create()
  {
    return Inertia::render('Dashboard/Invoice/Index', [
      'status' => session('status'),
      'clientes' => Cliente::get([
        'cli_id',
        'cli_nom',
        'cli_ape',
        'cli_ema'
      ])
    ]);
  }

  public function generate(Request $request)
  {
    $clientId = $request->id;
    $client = Cliente::find($clientId);
    $allOrders = Pedido::where('fk_cli_id', $clientId)->with('EstadoPedido')->get();

    // Generate invoice
    PDF::setOption([
      'isRemoteEnabled' => true,
      'isHtml5ParserEnabled' => true,
      'isPhpEnabled' => true,
      'isFontSubsettingEnabled' => true,
    ]);
    $pdf = PDF::loadView('invoice', [
      'client' => $client,
      'orders' => $allOrders,
      'fecha' => now(),
    ]);

    return $pdf->download('invoice.pdf');
  }
}
