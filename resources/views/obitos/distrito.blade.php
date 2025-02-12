<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastros Desatualizados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <style>
        /* Ajustando o tamanho das DataTables */
        #unidadesTable, #dadosDesatualizadosTable {
            width: 100%; /* Garantir que a tabela ocupe toda a largura disponível */
            table-layout: auto; /* Melhor controle do layout da tabela */
        }

        /* Melhorando a visualização de colunas largas */
        .dataTables_wrapper .dataTables_scroll {
            overflow-x: auto;
        }

        .btn-group {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <div class="d-flex justify-content-start mb-4">
        <a href="{{ url('/') }}" class="btn btn-primary">Ir para Início</a>
    </div>

    <div class="container mt-5">
        <div class="form-container">
            <h1 class="form-header mb-4">Cadastros Desatualizados</h1>

            <!-- Formulário de Filtros -->
            <form action="{{ route('obitos.distrito') }}" method="GET">
                <div class="row mb-4">
                    <!-- Filtro de Distrito -->
                    <div class="col-md-6">
                        <label for="distrito" class="form-label">Distrito</label>
                        <select name="distrito" id="distrito" class="form-select" required>
                            <option value="">Selecione o Distrito</option>
                            <option value="todos" {{ request('distrito') == 'todos' ? 'selected' : '' }}>Todos</option>
                            @foreach($distritos as $distrito)
                                <option value="{{ $distrito->no_disa }}" 
                                    {{ request('distrito') == $distrito->no_disa ? 'selected' : '' }}>{{ $distrito->no_disa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro de Unidade de Saúde -->
                    <div class="col-md-6">
                        <label for="unidade_saude" class="form-label">Unidade de Saúde</label>
                        <select name="unidade_saude" id="unidade_saude" class="form-select" 
                            {{ request('distrito') && request('distrito') !== 'todos' ? '' : 'disabled' }} required>
                            <option value="">Selecione a Unidade de Saúde</option>
                            @foreach($unidadesSaude as $unidade)
                                <option value="{{ $unidade->nu_cnes }}" 
                                    {{ request('unidade_saude') == $unidade->nu_cnes ? 'selected' : '' }}>{{ $unidade->no_unidade }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Botão de Filtrar -->
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-custom">Filtrar</button>
                    </div>
                </div>
            </form>

            <!-- Exibir Total de Cadastros Desatualizados -->
            @if(isset($totalDiasAtraso) && $totalDiasAtraso > 0)
                <div class="row mt-4 d-flex justify-content-between align-items-center">
                    <div class="col-auto">
                        <p><strong>Total de Dias de Atraso:</strong> 
                            {{ $totalDiasAtraso }}
                        </p>
                    </div>
                </div>
            @endif

            @if(isset($dadosUnidades) && $dadosUnidades->isNotEmpty())
                <table id="unidadesTable" class="table table-bordered mt-4">
                    <thead>
                        <tr>
                            <th>Unidade de Saúde</th>
                            <th>CNES</th>
                            <th>Dias de Atraso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dadosUnidades as $unidade)
                            <tr>
                                <td>{{ $unidade->no_unidade }}</td>
                                <td>{{ $unidade->nu_cnes }}</td>
                                <td>{{ $unidade->dias_atraso }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>
    </div>

    <!-- Adiciona a tabela de dados desatualizados -->
@if(isset($dadosDesatualizados) && $dadosDesatualizados->isNotEmpty())
    <table id="dadosDesatualizadosTable" class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Cidadão</th>
                <th>Data de Nascimento</th>
                <th>Nome da Mãe</th>
                <th>CNS</th>
                <th>CNES</th>
                <th>CPF</th>
                <th>Unidade de Saúde</th>
                <th>Dias de Atraso</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dadosDesatualizados as $dados)
                <tr>
                    <td>{{ $dados->no_cidadao }}</td>
                    <td>{{ \Carbon\Carbon::parse($dados->dt_nascimento)->format('d/m/Y') }}</td>
                    <td>{{ $dados->no_mae }}</td>
                    <td>{{ $dados->nu_cns }}</td>
                    <td>{{ $dados->nu_cnes }}</td>
                    <td>{{ $dados->nu_cpf }}</td>
                    <td>{{ $dados->no_unidade_saude }}</td>
                    <td>{{ $dados->dias_atraso }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>Nenhum dado encontrado.</p>
@endif

    <!-- Script do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script de Ajax para atualizar a lista de Unidades de Saúde dinamicamente -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
    document.getElementById('distrito').addEventListener('change', function() {
        var distrito = this.value;
        var unidadeSaudeSelect = document.getElementById('unidade_saude');
        unidadeSaudeSelect.disabled = (distrito === 'todos'); // Desativa o filtro de unidades se "Todos" for selecionado

        if (distrito && distrito !== 'todos') {
            fetch('/unidades-saude?distrito=' + distrito)
                .then(response => response.json())
                .then(data => {
                    unidadeSaudeSelect.innerHTML = '<option value="">Selecione a Unidade de Saúde</option>';
                    data.unidades.forEach(function(unidade) {
                        var option = document.createElement('option');
                        option.value = unidade.nu_cnes;
                        option.textContent = unidade.no_unidade;
                        unidadeSaudeSelect.appendChild(option);
                    });
                    unidadeSaudeSelect.disabled = false;
                })
                .catch(err => console.log('Erro ao carregar unidades: ', err));
        }
    });

    $(document).ready(function() {
        $('#unidadesTable').DataTable({
            language: { url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt_br.json" },
            paging: true,
            searching: true,
            info: true,
            lengthChange: true
        });
    });

    $(document).ready(function() {
        // Inicializar a DataTable para a tabela de dados desatualizados
        $('#dadosDesatualizadosTable').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt_br.json"
            },
            paging: true,
            searching: true,
            info: true,
            lengthChange: true,
            dom: 'Bfrtip',
            buttons: [
                'excel', 'pdf'
            ]
        });
    });
</script>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

</body>

</html>
