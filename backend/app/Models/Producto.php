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
        return $this->hasMany(Factura::class, 'id_producto');
    }
    // Desactiva created_at y updated_at
    public $timestamps = false;
}
