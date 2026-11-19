<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Eco35Controller extends Controller
{
    // Mostrar Dashboard, Mapa y Formularios (Módulos 2, 3, 4 y 5)
    public function index()
    {
        $especies = DB::table('especies')->get();
        $alertas = DB::table('alertas')->get();
        $siembras = DB::table('siembras')
            ->join('especies', 'siembras.especie_id', '=', 'especies.id')
            ->select('siembras.*', 'especies.nombre_cientifico')
            ->get();

        $totalPlantados = $siembras->sum('cantidad');
        $totalSupervivientes = $siembras->sum('supervivientes_estimados');
        $estadoMeta = $totalPlantados >= 500 ? 'Alcanzada' : 'En Progreso';

        return view('eco35', compact(
            'especies', 'alertas', 'siembras', 
            'totalPlantados', 'totalSupervivientes', 'estadoMeta'
        ));
    }

    // Guardar Siembra con Algoritmo de Supervivencia (Módulo 4)
    public function guardarSiembra(Request $request)
    {
        $especie = DB::table('especies')->where('id', $request->especie_id)->first();
        $aguaRoja = $request->has('agua_roja');

        // Fórmula oficial de Eco35:
        // Supervivientes = REDONDEAR(Cantidad * Tasa_Base * (Agua_Roja ? 0.75 : 1.00), 0)
        $factorHidrico = $aguaRoja ? 0.75 : 1.00;
        $supervivientes = round($request->cantidad * $especie->tasa_base * $factorHidrico);

        DB::table('siembras')->insert([
            'especie_id' => $especie->id,
            'cantidad' => $request->cantidad,
            'agua_roja' => $aguaRoja,
            'supervivientes_estimados' => $supervivientes,
            'user_id' => auth()->id() ?? 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('mensaje', "Siembra registrada. Supervivencia estimada: $supervivientes árboles.");
    }

    // Guardar Alerta Georreferenciada en el Mapa (Módulo 3)
    public function guardarAlerta(Request $request)
    {
        DB::table('alertas')->insert([
            'tipo' => $request->tipo,
            'semaforo' => $request->semaforo,
            'descripcion' => $request->descripcion,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'user_id' => auth()->id() ?? 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('mensaje', 'Alerta agregada al mapa del CBTa 35.');
    }
    public function eliminarAlerta($id)
    {
        DB::table('alertas')->where('id', $id)->delete();
        return back()->with('mensaje', 'Alerta eliminada del mapa y de la base de datos.');
    }
}