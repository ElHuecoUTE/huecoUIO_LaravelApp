<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoPedido extends Model
{
    protected $table = 'estado_pedido';
    protected $primaryKey = 'est_ped_id';
    protected $fillable = ['est_ped_nom'];
    public $timestamps = false;

    public function pedido()
    {
        return $this->hasMany(Pedido::class, 'fk_est_ped_id', 'ped_id');
    }
}
