<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
        $facturas = Factura::join('clientes', 'facturas.id_cliente', '=', 'clientes.id_cliente')
        ->join('productos', 'facturas.id_producto', '=', 'productos.id_producto')
        ->select(
            'facturas.*',
            'clientes.nombre as nombres_cliente',
            'clientes.apellido as apellidos_cliente',
            'productos.nombre as nombre_producto',
            'productos.precio as precio_producto'
        )
        ->get();

    $facturas->transform(function ($factura) {
        // Formato: $1.000.000
        $factura->precio_producto_formateado = '$' . number_format($factura->precio_producto, 0, ',', '.');

        // Formato de fecha: dd/mm/yyyy
        $factura->fecha_formateada = \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y');

        return $factura;
    });

    return response()->json($facturas);
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $factura = new Factura();
        $factura->id_cliente = $request->input('id_cliente');
        $factura->id_producto = $request->input('id_producto');
        $factura->fecha = $request->input('fecha');
        $factura->save();

        return response()->json($factura, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Factura $factura)
    {
        return response()->json($factura);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Factura $factura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Factura $factura)
    {
        $factura->id_cliente = $request->input('id_cliente');
        $factura->id_producto = $request->input('id_producto');
        $factura->fecha = $request->input('fecha');
        $factura->save();

        return response()->json(['message' => 'Factura actualizada correctamente.', 'factura' => $factura]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Factura $factura)
    {
        $factura->delete();
        return response()->json(['message' => 'Factura eliminada correctamente.']);
    }
}
