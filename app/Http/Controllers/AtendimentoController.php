<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtendimentoController extends Controller
{
    public function index(Request $request)
    {
        // Obter o ano atual ou o ano enviado como parâmetro
        $anoFiltro = $request->get('ano', date('Y'));

        // Dados do gráfico (total de óbitos e atendimentos por ano)
        $dados = DB::table('tb_dados_atendimento_obito')
            ->select(
                DB::raw('EXTRACT(YEAR FROM dt_obito_sim) as ano_obito'),
                DB::raw('EXTRACT(YEAR FROM dt_ult_atendimento) as ano_atendimento'),
                DB::raw('COUNT(*) as total')
            )
            ->whereRaw('EXTRACT(YEAR FROM dt_obito_sim) = ?', [$anoFiltro])
            ->orWhereRaw('EXTRACT(YEAR FROM dt_ult_atendimento) = ?', [$anoFiltro])
            ->groupBy(
                DB::raw('EXTRACT(YEAR FROM dt_obito_sim)'),
                DB::raw('EXTRACT(YEAR FROM dt_ult_atendimento)')
            )
            ->get();

        // Total de atendimentos pós-óbito
        $totalAtendimentosPosObitos = DB::table('tb_dados_atendimento_obito')
            ->whereColumn('dt_ult_atendimento', '>', 'dt_obito_sim') // Filtro de atendimentos após óbitos
            ->count();

        // Dados do gráfico por mês (agrupados por mês)
        $dadosPorMes = DB::table('tb_dados_atendimento_obito')
            ->select(
                DB::raw('EXTRACT(MONTH FROM dt_obito_sim) as mes'),
                DB::raw('COUNT(*) as obitos'),
                DB::raw('COUNT(*) as atendimentos')
            )
            ->whereYear('dt_obito_sim', $anoFiltro)
            ->groupBy(DB::raw('EXTRACT(MONTH FROM dt_obito_sim)'))
            ->get();

        // Passar os dados e o filtro de ano para a view
        return view('obitos.atendimentos', [
            'dados' => $dados,
            'anoFiltro' => $anoFiltro,
            'totalAtendimentosPosObitos' => $totalAtendimentosPosObitos, // Adicionando total à view
            'dadosPorMes' => $dadosPorMes // Passando dados por mês para o gráfico
        ]);
    }

    public function detalhesAtendimentosPosObitos(Request $request)
    {
        // Consultar os dados de atendimentos pós-óbito
        $dados = DB::table('tb_dados_atendimento_obito')
            ->select('no_cidadao', 'dt_obito_sim', 'dt_ult_atendimento')
            ->whereColumn('dt_ult_atendimento', '>', 'dt_obito_sim') // Filtro de atendimentos após óbitos
            ->get();

        // Retornar para a view com os dados
        return view('obitos.pos_obitos', [
            'dados' => $dados,
        ]);
    }
}
