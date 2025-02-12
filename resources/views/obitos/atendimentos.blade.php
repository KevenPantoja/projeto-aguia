<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Atendimentos e Óbitos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="d-flex justify-content-start mb-4">
        <a href="{{ url('/') }}" class="btn btn-primary">Ir para Início</a>
    </div>

    <div class="container mt-5">
        <h1 class="text-center">Gráfico de Óbitos e Atendimentos</h1>

        <!-- Campo de filtro de ano -->
        <form method="GET" action="{{ route('atendimentos') }}">
            <div class="form-group">
                <label for="ano">Selecione o Ano:</label>
                <select name="ano" id="ano" class="form-control" onchange="this.form.submit()">
                    @foreach(range(2020, date('Y')) as $ano)
                        <option value="{{ $ano }}" @if($ano == $anoFiltro) selected @endif>{{ $ano }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <canvas id="graficoAtendimentosObitos" width="400" height="200"></canvas>

        <script>
            const ctx = document.getElementById('graficoAtendimentosObitos').getContext('2d');

            // Filtro para o ano selecionado (exemplo: 2024)
            const anoSelecionado = {{ $anoFiltro ?? date('Y') }};

            // Meses do ano para o eixo X
            const meses = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];

            // Dados do gráfico por ano e mês
            const dados = @json($dadosPorMes); // Exemplo: [{mes: 1, obitos: 10, atendimentos: 5}, {mes: 2, obitos: 8, atendimentos: 7}, ...]
            
            // Separando os dados de óbitos e atendimentos por mês
            const obitosPorMes = dados.map(item => item.obitos);
            const atendimentosPorMes = dados.map(item => item.atendimentos);

            // Definição do gráfico
            const graficoAtendimentosObitos = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: meses, // Meses no eixo X
                    datasets: [
                        {
                            label: 'Óbitos',
                            data: obitosPorMes,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Atendimentos',
                            data: atendimentosPorMes,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Total'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Meses'
                            }
                        }
                    }
                }
            });
        </script>
    </div>

    <!-- Total de Atendimentos Pós-Óbitos como link -->
    <div class="mt-4">
        <h2>Total de Atendimentos Pós-Óbitos: 
            <a href="{{ route('detalhes.atendimentos.pos.obitos') }}" class="text-primary">
                {{ $totalAtendimentosPosObitos }}
            </a>
        </h2>
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} - Projeto-aguia</p>
    </footer>
</body>
</html>
