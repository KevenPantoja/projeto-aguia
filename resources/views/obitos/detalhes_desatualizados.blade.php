<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes dos Cadastros Desatualizados</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <style>
        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }
        .page-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .btn-custom {
            margin-bottom: 20px;
        }
        .dataTables_paginate a {
            padding: 8px 16px;
            margin: 0 2px;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-start mb-4">
            <a href="{{ route('obitos.distrito') }}" class="btn btn-primary btn-custom">Ir para Distritos</a>
        </div>

        <h1 class="page-header">Detalhes dos Cadastros Desatualizados</h1>

        @if (isset($dadosDesatualizados) && $dadosDesatualizados->isNotEmpty())
            <table id="dadosTable" class="table table-bordered mt-4">
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
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
    $(document).ready(function() {
        $('#dadosTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'pdf'
            ],
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt_br.json"
            },
            paging: true,
            searching: true,
            info: true,
            lengthChange: true
        });
    });
</script>

</body>

</html>
