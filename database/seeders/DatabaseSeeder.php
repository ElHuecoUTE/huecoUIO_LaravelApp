<?php

namespace Database\Seeders;

use App\Models\EstadoInventario;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);

        DB::table('estado_inventario')->insert([
            ['est_inv_id' => 1, 'est_inv_des' => 'Activo'],
            ['est_inv_id' => 2, 'est_inv_des' => 'Inactivo'],
            ['est_inv_id' => 3, 'est_inv_des' => 'Agotado']
        ]);

        DB::table('estado_pedido')->insert([
            ['est_ped_id' => 1, 'est_ped_nom' => 'Pendiente'],
            ['est_ped_id' => 2, 'est_ped_nom' => 'Enviado'],
            ['est_ped_id' => 3, 'est_ped_nom' => 'Entregado'],
            ['est_ped_id' => 4, 'est_ped_nom' => 'Cancelado']
        ]);

        DB::table('estado_producto')->insert([
            ['est_pro_id' => 1, 'est_pro_nom' => 'Activo'],
            ['est_pro_id' => 2, 'est_pro_nom' => 'Inactivo']
        ]);

        DB::table('tipo_registro_inventario')->insert([
            ['tip_reg_inv_id' => 1, 'tip_reg_inv_des' => 'Entrada'],
            ['tip_reg_inv_id' => 2, 'tip_reg_inv_des' => 'Salida']
        ]);
        DB::table('iva')->insert(
          ['iva_val' => 15]
        );

    }
}
