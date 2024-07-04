<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoInventario extends Model
{
    protected $table = 'estado_inventario';
    protected $primaryKey = 'est_inv_id';
    protected $fillable = [
        'est_inv_des'
    ];
    public $timestamps = false;

    public function inventario()
    {
        return $this->hasMany(Inventario::class, 'fk_est_inv_id', 'inv_id');
    }
}
