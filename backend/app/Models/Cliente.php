<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre', 'apellido', 'direccion', 'fecha_nacimiento', 'telefono', 'email', 'categoria',
    ];

    public function facturas()
    {
        return $this->hasMany(Factura::class, 'id_cliente', 'id_cliente');
    }
    // Define la llave primaria
    protected $primaryKey = 'id_cliente';
     // Desactiva created_at y updated_at
    public $timestamps = false;
}
