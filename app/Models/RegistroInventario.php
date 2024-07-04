<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroInventario extends Model
{
    protected $table = 'registro_inventario';
    protected $primaryKey = 'reg_inv_id';
    protected $fillable = [
      'fk_inv_id',
      'reg_inv_fec',
      'reg_inv_can',
      'fk_reg_inv_tip'
    ];
    public $timestamps = false;

    public function inventario()
    {
        return $this->belongsTo(Inventario::class, 'fk_inv_id', 'inv_id');
    }

    public function tipoRegistroInventario()
    {
        return $this->belongsTo(TipoRegistroInventario::class, 'fk_reg_inv_tip', 'reg_inv_tip_id');
    }
}
