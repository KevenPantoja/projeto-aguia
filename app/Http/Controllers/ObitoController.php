<?php
namespace App\Http\Controllers;

use App\Models\Obito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ObitoController extends Controller
{
    public function index()
    {
        $dados = Obito::select(
            DB::raw('EXTRACT(YEAR FROM dt_obito_sim) as ano'),
            DB::raw('COUNT(*) as total_mortes'),
            DB::raw('SUM(CASE WHEN dt_obito_sim IS NOT NULL AND dt_obito IS NULL THEN 1 ELSE 0 END) as obitos_na_dt_obito_sim')
        )
        ->groupBy('ano')
        ->orderBy('ano')
        ->get();

        $totalGeral = Obito::count();

        $obitosNaDtObitoSim = Obito::whereNotNull('dt_obito_sim')
            ->whereNull('dt_obito')
            ->count();

        return view('obitos.index', compact('dados', 'totalGeral', 'obitosNaDtObitoSim'));
    }


    public function listanomes()
    {
        $obitos = Obito::all()->map(function ($obito) {
            $obito->dt_nascimento = \Carbon\Carbon::parse($obito->dt_nascimento)->format('d/m/Y');
            $obito->dt_obito_sim = \Carbon\Carbon::parse($obito->dt_obito_sim)->format('d/m/Y');
            return $obito;
        });

        return view('obitos.lista', compact('obitos'));
    }
}