<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iva extends Model
{
  protected $table = 'iva';
  protected $primaryKey = 'iva_id';
  protected $fillable = [
    'iva_val'
  ];
  public $timestamps = false;
}
