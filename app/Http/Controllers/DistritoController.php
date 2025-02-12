<?php

namespace App\Http\Controllers;

use App\Models\Distrito;
use App\Models\UnidadeSaude;
use App\Models\DadosDesatualizados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistritoController extends Controller
{
    /**
     * Exibe a página principal com os filtros de distrito e unidade de saúde.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */

    public function getUnidadesSaude(Request $request)
    {
        if ($request->has('distrito') && $request->distrito != '') {
            // Buscar as unidades de saúde relacionadas ao distrito
            $unidades = UnidadeSaude::where('no_disa', $request->distrito)
                ->select('nu_cnes', 'no_unidade')
                ->get();

            return response()->json(['unidades' => $unidades]);
        }

        return response()->json(['unidades' => []]); // Retorna um array vazio se não houver distrito
    }

    public function index(Request $request)
{
    // Buscar todos os distritos
    $distritos = Distrito::select('no_disa')->distinct()->get();

    // Variáveis para armazenar os dados filtrados
    $unidadesSaude = [];
    $totalDiasAtraso = 0; // Variável para armazenar o total de dias de atraso
    $dadosUnidades = collect(); // Coleção para armazenar os dados das unidades
    $dadosDesatualizados = collect(); // Coleção para armazenar os dados desatualizados filtrados

    if ($request->has('distrito')) {
        $distrito = $request->distrito;

        if ($distrito === 'todos') {
            // Retornar todas as unidades de saúde e somar os dias de atraso de todas
            $dadosUnidades = DadosDesatualizados::select(
                'no_unidade_saude as no_unidade',
                'nu_cnes',
                DB::raw('SUM(dias_atraso) as dias_atraso')
            )
            ->groupBy('no_unidade_saude', 'nu_cnes')
            ->get();

            $totalDiasAtraso = $dadosUnidades->sum('dias_atraso');
        } else {
            // Buscar as unidades de saúde relacionadas ao distrito selecionado
            $unidadesSaude = UnidadeSaude::where('no_disa', $distrito)
                ->select('nu_cnes', 'no_unidade')
                ->get();

            // Verificar se uma unidade de saúde foi selecionada
            if ($request->has('unidade_saude') && $request->unidade_saude != '') {
                // Buscar os dados desatualizados filtrados por unidade de saúde
                $dadosDesatualizados = DadosDesatualizados::select(
                    'no_cidadao',
                    DB::raw('MAX(dt_nascimento) as dt_nascimento'),
                    DB::raw('MAX(no_mae) as no_mae'),
                    DB::raw('MAX(nu_cns) as nu_cns'),
                    DB::raw('MAX(nu_cpf) as nu_cpf'),
                    DB::raw('MAX(nu_cnes) as nu_cnes'),
                    DB::raw('MAX(no_unidade_saude) as no_unidade_saude'),
                    DB::raw('SUM(dias_atraso) as dias_atraso')
                )
                ->where('nu_cnes', $request->unidade_saude) // Filtra pela unidade de saúde selecionada
                ->groupBy('no_cidadao')
                ->get();
            }
        }
    }

    // Retornar para a view com os dados
    return view('obitos.distrito', compact('distritos', 'unidadesSaude', 'totalDiasAtraso', 'dadosUnidades', 'dadosDesatualizados'));
}


    public function distrito(Request $request)
    {
        // Verifica se a unidade de saúde foi selecionada
        $cnes = $request->input('unidade_saude');
    
        // Realiza a busca pelos cadastros desatualizados
        $dadosDesatualizados = DadosDesatualizados::select(
            'no_cidadao',
            DB::raw('MAX(dt_nascimento) as dt_nascimento'),
            DB::raw('MAX(no_mae) as no_mae'),
            DB::raw('MAX(nu_cns) as nu_cns'),
            DB::raw('MAX(nu_cpf) as nu_cpf'),
            DB::raw('MAX(nu_cnes) as nu_cnes'),
            DB::raw('MAX(no_unidade_saude) as no_unidade_saude'),
            DB::raw('SUM(dias_atraso) as dias_atraso')
        )
        ->when($cnes, function ($query, $cnes) {
            return $query->where('nu_cnes', $cnes); // Filtra pelo CNES da unidade de saúde, se fornecido
        })
        ->groupBy('no_cidadao')
        ->paginate(200000); // Paginação de 200.000 itens por página
    
        // Passa os dados para a view
        return view('obitos.detalhes_desatualizados', compact('dadosDesatualizados'));
    }
}
