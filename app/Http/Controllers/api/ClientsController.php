<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Inertia\Inertia;
use Inertia\Response;

class ClientsController extends Controller
{
    // Documentation by Dante
    /*
    json example
    store:
    Url: http://localhost:8000/api/clientes
    {
      "nombre": "string",
      "apellido": "string",
      "telefono": "string",
      "email": "string",
      "direccion": "string",
      "sexo": "string"
    }
    update:
    Url: http://localhost:8000/api/clientes/{id}
    {
      "nombre": "string",
      "apellido": "string",
      "telefono": "string",
      "email": "string",
      "direccion": "string",
      "sexo": "string",
      "estado": "boolean"
    }
    show:
    Url: http://localhost:8000/api/clientes/{id}
    Destroy:
    Url: http://localhost:8000/api/clientes/{id}
    */
    public function index(Request $request): JsonResponse
    {
      return $this->sendResponse(Cliente::all(), 'Clientes recuperados correctamente');
    }

    public function store(Request $request): JsonResponse
    {
      try{
        $request->validate([
          'nombre' => 'required|string|max:30',
          'apellido' => 'required|string|max:30',
          'telefono' => 'required|string|max:10|min:10|regex:/^[0-9]+$/',
          'email' => 'required|email|max:50|lowercase',
          'direccion' => 'required|string|max:100',
          'sexo' => 'required|string|max:1|in:m,f,o',
        ]);

        $details = [
          'cli_nom' => $request->nombre,
          'cli_ape' => $request->apellido,
          'cli_tel' => $request->telefono,
          'cli_ema' => $request->email,
          'cli_dir' => $request->direccion,
          'cli_sex' => $request->sexo
        ];

        $cliente = Cliente::create($details);
        return $this->sendResponse($cliente, 'Cliente creado correctamente');
      }catch (\Exception $e){
        return $this->sendError('Error al crear el cliente', $e->getMessage(), 400);
      }
    }

    public function show($id): JsonResponse
    {
      try{
        return $this->sendResponse(Cliente::findOrFail($id), 'Cliente recuperado correctamente');
      } catch (\Exception $e){
        return $this->sendError('Error al encontrar el cliente', $e->getMessage(), 400);
      }
    }

    public function update($id, Request $request): JsonResponse
    {
      try{
        $request->validate([
          'nombre' => 'required|string|max:30',
          'apellido' => 'required|string|max:30',
          'telefono' => 'required|string|max:10|min:10|regex:/^[0-9]+$/',
          'email' => 'required|email|max:50|lowercase',
          'direccion' => 'required|string|max:100',
          'sexo' => 'required|string|max:1|in:m,f,o',
          'estado' => 'required|boolean'
        ]);

        $details = [
          'cli_nom' => $request->nombre,
          'cli_ape' => $request->apellido,
          'cli_tel' => $request->telefono,
          'cli_ema' => $request->email,
          'cli_dir' => $request->direccion,
          'cli_sex' => $request->sexo,
          'cli_est' => $request->estado
        ];

        $cliente = Cliente::findOrFail($id);
        $cliente->update($details);
        return $this->sendResponse($cliente, 'Cliente actualizado correctamente');
      } catch (\Exception $e){
        return $this->sendError('Error al actualizar el cliente', $e->getMessage(), 400);
      }
    }

    public function destroy($id): JsonResponse
    {
      try{
        Cliente::findOrFail($id)->update(['cli_est' => 0]);
        return $this->sendResponse([], 'Cliente deshabilitado correctamente');
      } catch (\Exception $e){
        return $this->sendError('Error al eliminar el cliente', $e->getMessage(), 400);
      }
    }

}
