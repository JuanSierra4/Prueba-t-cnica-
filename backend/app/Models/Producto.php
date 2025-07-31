<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_producto';

    protected $fillable = ['nombre', 'precio', 'stock'];

    public function facturas()
    {
        return $this->belongsToMany(Factura::class, 'facturas_productos', 'producto_id', 'factura_id');
    }   
}
