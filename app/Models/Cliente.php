<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{

    protected $table = 'cliente';

    protected $primaryKey = 'cli_id';
    protected $fillable = [
        'cli_nom',
        'cli_ape',
        'cli_tel',
        'cli_ema',
        'cli_dir',
        'cli_sex',
        'created_at',
        'updated_at',
    ];
    public $timestamps = true;

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'fk_cli_id', 'cli_id');
    }

    public function ventas()
    {
        return $this->hasMany(Ventas::class, 'fk_cli_id', 'cli_id');
    }
}
