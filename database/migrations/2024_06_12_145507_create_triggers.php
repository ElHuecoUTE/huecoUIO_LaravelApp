<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER IF NOT EXISTS producto_AFTER_INSERT AFTER INSERT ON producto
            FOR EACH ROW
            BEGIN
              insert into inventario (fk_pro_id, inv_stock, fk_est_inv_id) values (New.pro_id, 0, 1);
            END'
        );
        DB::unprepared('
            CREATE TRIGGER IF NOT EXISTS detalle_pedido_BEFORE_INSERT BEFORE INSERT ON detalle_pedido
            FOR EACH ROW
            BEGIN
              update inventario i set i.inv_stock = i.inv_stock - New.det_ped_can where New.fk_pro_id = i.fk_pro_id;
            END'
        );
        DB::unprepared('
            CREATE TRIGGER IF NOT EXISTS inventario_BEFORE_UPDATE BEFORE UPDATE ON inventario
            FOR EACH ROW
            BEGIN
              IF NEW.inv_stock != OLD.inv_stock then
                insert into
                  registro_inventario(fk_inv_id, reg_inv_fec, reg_inv_can, fk_reg_inv_tip)
                        values (
                        NEW.inv_id,
                        current_date(),
                        if(NEW.inv_stock > OLD.inv_stock, NEW.inv_stock - OLD.inv_stock, OLD.inv_stock - NEW.inv_stock),
                        if(NEW.inv_stock > OLD.inv_stock, 1, 2));
                End if;
            END'
        );
        DB::unprepared('
          CREATE TRIGGER IF NOT EXISTS pedido_BEFORE_UPDATE BEFORE UPDATE ON pedido
          FOR EACH ROW
          BEGIN
            IF NEW.fk_est_ped_id = 4 THEN
              update inventario i
                inner join detalle_pedido dp on
                  i.fk_pro_id = dp.fk_pro_id
              set
                i.inv_stock = i.inv_stock + dp.det_ped_can
              where dp.fk_ped_id = NEW.ped_id;
            END IF;
          END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS producto_AFTER_INSERT');
        DB::unprepared('DROP TRIGGER IF EXISTS pedido_BEFORE_UPDATE');
        DB::unprepared('DROP TRIGGER IF EXISTS inventario_BEFORE_UPDATE');
        DB::unprepared('DROP TRIGGER IF EXISTS detalle_pedido_BEFORE_INSERT');
    }
};
