<?php

namespace App\Http\Controllers;

use App\Models\NotificacaoSinan;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
    public function index(Request $request)
    {
        // Recupera o último ano e mês registrado no banco
        $ultimoAnoMes = NotificacaoSinan::select('ano_notific', 'mes_notific')
            ->orderBy('ano_notific', 'desc')
            ->orderBy('mes_notific', 'desc')
            ->first();

        // Recupera os valores de filtro de ano e mês, com valores padrão
        $ano_inicio = $request->input('ano_inicio', $ultimoAnoMes ? $ultimoAnoMes->ano_notific : date('Y'));
        $ano_fim = $request->input('ano_fim', $ano_inicio); // O ano final pode ser igual ao ano de início
        $mes_inicio = $request->input('mes_inicio', '01');
        $mes_fim = $request->input('mes_fim', '12');

        // Filtra as notificações com base no intervalo de ano e mês
        $notificacoes = NotificacaoSinan::whereBetween('ano_notific', [$ano_inicio, $ano_fim])
            ->whereBetween('mes_notific', [$mes_inicio, $mes_fim])
            ->get();

        // Dados para o gráfico, agrupados por tipo de agravo
        $dadosGrafico = NotificacaoSinan::selectRaw('tipo_agravo, SUM(quant_notific) as total_notificacoes')
            ->whereBetween('ano_notific', [$ano_inicio, $ano_fim])
            ->whereBetween('mes_notific', [$mes_inicio, $mes_fim])
            ->groupBy('tipo_agravo')
            ->get();

        // Retorna a view com os dados filtrados
        return view('obitos.notificacoes', compact('notificacoes', 'dadosGrafico', 'ano_inicio', 'ano_fim', 'mes_inicio', 'mes_fim'));
    }

    public function getDatatable(Request $request)
    {
        // Consulta para obter as notificações com base nos filtros de ano e mês
        $query = DB::table('tb_dados_notificacoes_sinan')
            ->select('tipo_agravo', 'mes_notific', 'ano_notific', 'quant_notific');
        
        if ($request->ano) {
            $query->where('ano_notific', $request->ano);
        }
        if ($request->mes) {
            $query->where('mes_notific', $request->mes);
        }

        $data = $query->get();
    
        return response()->json($data);
    }
}
