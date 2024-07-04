<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClientsController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Dashboard/Clients/Index', [
          'status' => session('status'),
          'data' => Cliente::where('cli_est', true)->get(),
        ]);
    }

    public function show(Request $request): \Illuminate\Http\RedirectResponse
    {
      return redirect()->route('clientes');
    }

    public function store(Request $request): Response
    {
      $request->validate([
        'nombre' => 'required|string|max:30',
        'apellido' => 'required|string|max:30',
        'telefono' => 'required|string|max:10|min:10|regex:/^[0-9]+$/',
        'email' => 'required|email|max:50|lowercase',
        'direccion' => 'required|string|max:100',
        'sexo' => 'required|string|max:1|in:m,f,o',
      ]);

      $details =
      [
        'cli_nom' => $request->nombre,
        'cli_ape' => $request->apellido,
        'cli_tel' => $request->telefono,
        'cli_ema' => $request->email,
        'cli_dir' => $request->direccion,
        'cli_sex' => $request->sexo,
      ];

      $result = Cliente::create($details);
      return Inertia::render('Dashboard/Clients/Index', [
        'status' => session('status'),
        'result' => $result,
      ]);
    }

    public function update(Request $request): Response
    {
      $request->validate([
        'id' => 'required|integer',
        'nombre' => 'required|string|max:30',
        'apellido' => 'required|string|max:30',
        'email' => 'required|email|max:50|lowercase',
        'direccion' => 'required|string|max:100',
      ]);

      $details = [
        'cli_nom' => $request->nombre,
        'cli_ape' => $request->apellido,
        'cli_ema' => $request->email,
        'cli_dir' => $request->direccion,
        'updated_at' => now(),
      ];

      Cliente::findOrfail($request->id)->update($details);
      return Inertia::render('Dashboard/Clients/Index', [
        'status' => session('status'),
      ]);
    }

    public function destroy(Request $request): Response
    {
      $request->validate([
        'id' => 'required|integer',
      ]);

      Cliente::where('cli_id', $request->id)->update(['cli_est' => false]);

      return Inertia::render('Dashboard/Clients/Index', [
        'status' => session('status'),
      ]);
    }
}
