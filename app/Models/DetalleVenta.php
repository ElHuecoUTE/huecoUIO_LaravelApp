<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_venta';
    protected $primaryKey = 'det_ven_id';
    protected $fillable = [
        'fk_ven_id',
        'fk_ped_id',
    ];
    public $timestamps = false;

    public function venta()
    {
        return $this->belongsTo(Ventas::class, 'fk_ven_id', 'ven_id');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'fk_ped_id', 'ped_id');
    }
}
