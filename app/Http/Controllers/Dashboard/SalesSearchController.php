<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Inventario;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SalesSearchController extends Controller
{
  public function show($id): \Illuminate\Http\JsonResponse
  {
    if (empty($id)) {
      return $this->sendError('No se ha ingresado un valor de búsqueda');
    }

    $name = $id;
    // Find "Pedido" where "ped_id" is like the search value and "fk_est_ped_id" is 1
    $result = Pedido::where('ped_id', 'like', '%' . $name . '%')
      ->where('fk_est_ped_id', 1)
      ->get();

    return $this->sendResponse($result, 'Pedidos encontrados');
  }
}
