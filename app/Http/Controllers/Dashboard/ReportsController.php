<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\RegistroInventario;
use App\Models\TipoProducto;
use App\Models\Pedido;
use Inertia\Inertia;

class ReportsController extends Controller
{
  public function create()
  {
    // Return only created_at in the last 30 days as an array
    $createdClients = Cliente::whereDate('created_at', '>=', now()->subDays(30))->get("created_at")->toArray();
    $createdOrders = Pedido::whereDate('created_at', '>=', now()->subDays(30))->get("ped_fec")->toArray();
    $invLog = RegistroInventario::whereDate('reg_inv_fec', '>=', now()->subDays(30))->get([
      "reg_inv_fec",
      'fk_reg_inv_tip'
    ])->toArray();
    // Total count of products
    $productsCount = Producto::count();
    $productTypesCount = TipoProducto::count();
    return Inertia::render('Dashboard/Reports/Index', [
      'status' => session('status'),
      'stats' => [
        'clients' => $createdClients,
        'orders' => $createdOrders,
        'invLog' => $invLog,
        'products' => $productsCount,
        'productTypes' => $productTypesCount,
      ],
    ]);
  }
}
