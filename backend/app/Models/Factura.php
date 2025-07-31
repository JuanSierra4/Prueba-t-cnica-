<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;
     protected $primaryKey = 'num_factura';

    protected $fillable = ['fecha', 'id_cliente'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'facturas_productos', 'factura_id', 'producto_id');
    }
}
