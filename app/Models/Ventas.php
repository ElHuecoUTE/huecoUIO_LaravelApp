<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
  protected $table = 'ventas';
  protected $primaryKey = 'ven_id';
  protected $fillable = [
    'fk_cli_id',
    'fk_ped_id',
    'ven_tot',
    'created_at',
    'updated_at',
  ];
  public $timestamps = true;

  public function cliente()
  {
      return $this->belongsTo(Cliente::class, 'fk_cli_id', 'cli_id');
  }

  public function pedido()
  {
    return $this->belongsTo(Pedido::class, 'fk_ped_id', 'ped_id');
  }
}
