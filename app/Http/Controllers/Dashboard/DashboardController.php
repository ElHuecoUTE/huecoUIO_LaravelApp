<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\RegistroInventario;
use Inertia\Inertia;

class DashboardController extends Controller
{
  public function index()
    {
        return Inertia::render('Dashboard/Dashboard', [
          'status' => session('status'),
          'inventory_count' => Inventario::sum('inv_stock'),
          'last_30_days_entries' => RegistroInventario::where('reg_inv_fec', '>=', now()->subDays(30))->where('fk_reg_inv_tip', 1)->sum('reg_inv_can'),
          'last_30_days_exits' => RegistroInventario::where('reg_inv_fec', '>=', now()->subDays(30))->where('fk_reg_inv_tip', 2)->sum('reg_inv_can'),
        ]);
    }
}
