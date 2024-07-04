<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedido';
    protected $primaryKey = 'ped_id';
    protected $fillable = [
      'fk_cli_id',
      'ped_fec',
      'ped_tot',
      'created_at',
      'updated_at',
      'fk_est_ped_id'
    ];
    public $timestamps = true;

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'fk_cli_id', 'cli_id');
    }

    public function estadoPedido()
    {
        return $this->belongsTo(EstadoPedido::class, 'fk_est_ped_id', 'est_ped_id');
    }

    public function detallePedido()
    {
        return $this->hasMany(DetallePedido::class, 'fk_ped_id', 'ped_id');
    }

  public function Ventas()
  {
    return $this->hasOne(Ventas::class, 'fk_ped_id', 'ped_id');
  }
}
