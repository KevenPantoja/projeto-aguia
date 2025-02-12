@extends('obitos.base')

@section('content')
<div class="container mt-5">
    <!-- Botão de navegação para voltar à página inicial -->
    <div class="d-flex justify-content-start mb-4">
        <a href="http://127.0.0.1:8000/" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar para a Página Inicial
        </a>
    </div>

    <!-- Título da página -->
    <h1 class="mb-4 text-center">Notificações Sinan</h1>

    <form action="{{ route('obitos.notificacoes.index') }}" method="GET" class="mb-5">
    <div class="row">
        <!-- Campo para selecionar o ano de início -->
        <div class="col-md-3 col-12 mb-3">
            <div class="form-group">
                <label for="ano_inicio">Ano Início</label>
                <input type="number" id="ano_inicio" name="ano_inicio" value="{{ old('ano_inicio', $ano_inicio) }}" class="form-control" min="2000" max="{{ date('Y') }}" required>
            </div>
        </div>

        <!-- Campo para selecionar o ano de fim -->
        <div class="col-md-3 col-12 mb-3">
            <div class="form-group">
                <label for="ano_fim">Ano Fim</label>
                <input type="number" id="ano_fim" name="ano_fim" value="{{ old('ano_fim', $ano_fim) }}" class="form-control" min="2000" max="{{ date('Y') }}" required>
            </div>
        </div>

        <!-- Campo para selecionar o mês inicial -->
        <div class="col-md-3 col-12 mb-3">
            <div class="form-group">
                <label for="mes_inicio">Mês Início</label>
                <select id="mes_inicio" name="mes_inicio" class="form-control" required>
                    @foreach (['01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril', '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto', '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'] as $key => $month)
                        <option value="{{ $key }}" {{ old('mes_inicio', $mes_inicio) == $key ? 'selected' : '' }}>{{ $month }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Campo para selecionar o mês final -->
        <div class="col-md-3 col-12 mb-3">
            <div class="form-group">
                <label for="mes_fim">Mês Fim</label>
                <select id="mes_fim" name="mes_fim" class="form-control" required>
                    @foreach (['01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril', '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto', '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'] as $key => $month)
                        <option value="{{ $key }}" {{ old('mes_fim', $mes_fim) == $key ? 'selected' : '' }}>{{ $month }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Botão para enviar o formulário -->
    <button type="submit" class="btn btn-primary btn-lg">Filtrar</button>
</form>

    <!-- Botão para alternar entre os gráficos -->
    <div class="mb-3">
        <button id="toggleGraph" class="btn btn-info btn-lg">Mostrar Todos os Dados</button>
    </div>

        <!-- Gráfico de notificações -->
        <div class="card mb-4">
        <div class="card-header">
            <h4>Gráfico de Notificações</h4>
        </div>
        <div class="card-body">
            <div style="position: relative; width: 100%; height: 600px; max-width: 1200px; margin: 0 auto;">
                <canvas id="graficoNotificacoes"></canvas>
            </div>
            <div class="mt-3 text-center">
                <button id="downloadGraph" class="btn btn-success">Baixar Gráfico em PDF</button>
            </div>
        </div>
    </div>

    <!-- Tabela de notificações -->
    <div class="card">
        <div class="card-header">
            <h4>Tabela de Notificações</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Tipo de Agravo</th>
                            <th>Total de Notificações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $notificacoesAgrupadas = $notificacoes->groupBy('tipo_agravo')->map(function($items) {
                                return $items->sum('quant_notific');
                            });

                            $notificacoesOrdenadas = $notificacoesAgrupadas->filter(function($value) {
                                return $value > 0;
                            })->sortDesc();
                        @endphp

                        @foreach($notificacoesOrdenadas as $tipoAgravo => $totalNotificacoes)
                            <tr>
                                <td>{{ $tipoAgravo }}</td>
                                <td>{{ $totalNotificacoes }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3 text-center">
                <button id="downloadTable" class="btn btn-success">Baixar Tabela em PDF</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script>
const dadosGrafico = @json($dadosGrafico);

const dadosOrdenados = dadosGrafico.sort((a, b) => b.total_notificacoes - a.total_notificacoes);

let dadosLimitados = dadosOrdenados.slice(0, 20);

const obterDadosGrafico = (dados) => {
    const tiposAgravo = dados.map(item => item.tipo_agravo);
    const totais = dados.map(item => item.total_notificacoes);
    return { tiposAgravo, totais };
};

const ctx = document.getElementById('graficoNotificacoes').getContext('2d');
let chartData = obterDadosGrafico(dadosLimitados);

const graficoNotificacoes = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chartData.tiposAgravo,
        datasets: [{
            label: 'Total de Notificações',
            data: chartData.totais,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

document.getElementById('toggleGraph').addEventListener('click', () => {
    if (dadosLimitados.length === 20) {
        dadosLimitados = dadosOrdenados;
        document.getElementById('toggleGraph').textContent = 'Mostrar Apenas 20 Maiores';
    } else {
        dadosLimitados = dadosOrdenados.slice(0, 20);
        document.getElementById('toggleGraph').textContent = 'Mostrar Todos os Dados';
    }

    chartData = obterDadosGrafico(dadosLimitados);
    graficoNotificacoes.data.labels = chartData.tiposAgravo;
    graficoNotificacoes.data.datasets[0].data = chartData.totais;
    graficoNotificacoes.update();
});

// Baixar gráfico como PDF
const { jsPDF } = window.jspdf;
document.getElementById('downloadGraph').addEventListener('click', () => {
    const pdf = new jsPDF();
    pdf.text("Gráfico de Notificações", 10, 10);
    pdf.addImage(graficoNotificacoes.toBase64Image(), 'PNG', 10, 20, 180, 100);
    pdf.save("grafico_notificacoes.pdf");
});

// Baixar tabela como PDF
document.getElementById('downloadTable').addEventListener('click', () => {
    const pdf = new jsPDF();
    pdf.text("Tabela de Notificações", 10, 10);
    const table = document.getElementById('dataTable');
    pdf.autoTable({ html: table });
    pdf.save("tabela_notificacoes.pdf");
});
</script>
@endpush
