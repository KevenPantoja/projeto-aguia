<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gráfico de Óbitos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-start mb-4">
        <!-- Botão "Ir para Notificações" -->
        <a href="{{ route('obitos.notificacoes.index') }}" class="btn btn-primary">Ir para Notificações</a>
    </div>

    <!-- Botão "Ir para Atendimentos" -->
    <div class="d-flex justify-content-start mb-4">
        <a href="{{ route('atendimentos') }}" class="btn btn-success">Ir para Atendimentos</a>
    </div>

    <!-- Botão "Ir para Distrito" -->
    <div class="d-flex justify-content-start mb-4">
        <a href="{{ route('obitos.distrito') }}" class="btn btn-secondary">Ir para Distrito</a>
    </div>

    <div class="container mt-5">
        <h1 class="text-center">Gráfico de Óbitos por Ano Não Fechados no E-SUS</h1>
        
        <canvas id="graficoObitos" width="400" height="200"></canvas>

        <script>
            const ctx = document.getElementById('graficoObitos').getContext('2d');
            const anos = @json($dados->pluck('ano'));
            const totalMortes = @json($dados->pluck('total_mortes'));

            const graficoObitos = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: anos,
                    datasets: [{
                        label: 'Total de Mortes por Ano',
                        data: totalMortes,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Total de Mortes'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Ano'
                            }
                        }
                    }
                }
            });
        </script>

        <div class="mt-4">
            <h2>Dados registrados no SIM que se encontram em aberto no E-SUS: 
                <a href="{{ route('obitos.listaNomes') }}" class="text-primary">{{ $totalGeral }}</a>
            </h2>
        </div>
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} - Projeto-aguia</p>
    </footer>
</body>
</html>
