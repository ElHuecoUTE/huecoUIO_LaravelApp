<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoProducto extends Model
{
    protected $table = 'estado_producto';
    protected $primaryKey = 'est_pro_id';
    protected $fillable = ['est_pro_nom'];
    public $timestamps = false;

    public function producto()
    {
        return $this->hasMany(Producto::class, 'fk_est_pro_id', 'pro_id');
    }
}
