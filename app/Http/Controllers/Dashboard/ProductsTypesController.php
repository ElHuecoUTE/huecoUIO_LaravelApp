<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TipoProducto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductsTypesController extends Controller
{
  // Documentation by Dante

  // Esta función se encarga de renderizar la vista de los tipos de productos
  public function create(Request $request): Response
  {
    return Inertia::render('Dashboard/ProductsTypes/Index', [
      'status' => session('status'),
      'data' => TipoProducto::all()
    ]);
  }

  // Esta función se encarga de redirigir a la vista de los tipos de productos
  public function show(Request $request): \Illuminate\Http\RedirectResponse
  {
    return redirect()->route('tipos-productos');
  }

  // Esta función se encarga de almacenar un nuevo tipo de producto
  public function store(Request $request): Response
  {
    $request->validate([
      'nombre' => 'required|string|max:15',
    ]);

    $result = TipoProducto::create([
      'tip_pro_nom' => $request->input('nombre'),
    ]);

    return Inertia::render('Dashboard/ProductsTypes/Index', [
      'status' => session('status'),
      'result' => [
        'tip_pro_id' => $result->tip_pro_id,
        'tip_pro_nom' => $result->tip_pro_nom,
      ]
    ]);
  }

  // Esta función se encarga de actualizar un tipo de producto
  public function update(Request $request): Response
  {
    $request->validate([
      'id' => 'required|integer',
      'nombre' => 'required|string|max:15',
    ]);

    TipoProducto::where('tip_pro_id', $request->id)
      ->update([
        'tip_pro_nom' => $request->nombre,
      ]);

    return Inertia::render('Dashboard/ProductsTypes/Index', [
      'status' => session('status'),
    ]);
  }

  // Esta función se encarga de eliminar un tipo de producto
  public function destroy(Request $request): Response
  {
    $request->validate([
      'id' => 'required|integer',
    ]);

    TipoProducto::where('tip_pro_id', $request->id)->delete();

    return Inertia::render('Dashboard/ProductsTypes/Index', [
      'status' => session('status'),
    ]);
  }
}
