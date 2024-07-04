<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventario';
    protected $primaryKey = 'inv_id';
    protected $fillable = [
      'fk_pro_id',
      'inv_stock',
      'fk_est_inv_id'
    ];
    public $timestamps = false;

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'fk_pro_id', 'pro_id');
    }

    public function estadoInventario()
    {
        return $this->belongsTo(EstadoInventario::class, 'fk_est_inv_id', 'est_inv_id');
    }
}
