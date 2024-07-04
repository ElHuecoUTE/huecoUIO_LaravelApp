<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoProducto extends Model
{
    protected $table = 'tipo_producto';
    protected $primaryKey = 'tip_pro_id';
    protected $fillable = ['tip_pro_nom'];
    public $timestamps = false;

    public function producto()
    {
        return $this->hasMany(Producto::class, 'fk_tip_pro_id', 'pro_id');
    }
}
