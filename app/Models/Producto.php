<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
  protected $table = 'producto';
  protected $primaryKey = 'pro_id';
  protected $fillable = [
    'pro_nom',
    'pro_val',
    'fk_est_pro_id',
    'fk_tip_pro_id',
  ];
  public $timestamps = false;

  public function estadoProducto(): \Illuminate\Database\Eloquent\Relations\BelongsTo
  {
      return $this->belongsTo(EstadoProducto::class, 'fk_est_pro_id', 'est_pro_id');
  }

  public function tipoProducto(): \Illuminate\Database\Eloquent\Relations\BelongsTo
  {
      return $this->belongsTo(TipoProducto::class, 'fk_tip_pro_id', 'tip_pro_id');
  }

  public function inventario(): \Illuminate\Database\Eloquent\Relations\HasOne
  {
    return $this->hasOne(Inventario::class, 'fk_pro_id', 'pro_id');
  }
}
