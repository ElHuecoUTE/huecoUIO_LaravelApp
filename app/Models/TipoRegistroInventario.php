<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoRegistroInventario extends Model
{
    protected $table = 'tipo_registro_inventario';
    protected $primaryKey = 'tip_reg_inv_id';
    protected $fillable = ['tip_reg_inv_des'];
    public $timestamps = false;

    public function registroInventario()
    {
        return $this->hasMany(RegistroInventario::class, 'fk_reg_inv_tip', 'tip_reg_inv_id');
    }
}
