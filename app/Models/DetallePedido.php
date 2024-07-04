<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedido';
    protected $primaryKey = 'det_ped_id';
    protected $fillable = [
        'fk_ped_id',
        'fk_pro_id',
        'det_ped_can',
        'det_ped_pre'
    ];
    public $timestamps = false;

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'fk_ped_id', 'ped_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'fk_pro_id', 'pro_id');
    }
}
