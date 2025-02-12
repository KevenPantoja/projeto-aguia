<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atendimentos Pós-Óbitos</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <!-- DataTables Responsive CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Custom Styles -->
    <style>
        .container {
            margin-top: 50px;
        }

        table.dataTable {
            width: 100%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        table.dataTable thead th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }

        table.dataTable tbody td {
            text-align: center;
        }

        table.dataTable tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .dataTables_filter input {
            border-radius: 4px;
            padding: 5px;
            font-size: 14px;
        }

        .dataTables_length select {
            border-radius: 4px;
            padding: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <!-- Navegação -->
    <div class="d-flex justify-content-start mb-4">
        <a href="http://127.0.0.1:8000/atendimentos" class="btn btn-primary">Atendimentos</a>
    </div>

    <!-- Tabela de Atendimentos Pós-Óbitos -->
    <div class="container">
        <h1 class="text-center mb-4">Atendimentos Pós-Óbitos</h1>

        <table id="atendimentosPosObitos" class="display responsive nowrap" cellspacing="0">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Data de Óbito</th>
                    <th>Último Atendimento</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dados as $dado)
                    <tr>
                        <td>{{ $dado->no_cidadao }}</td>
                        <td>{{ \Carbon\Carbon::parse($dado->dt_obito_sim)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($dado->dt_ult_atendimento)->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
    $(document).ready(function() {
        $('#atendimentosPosObitos').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'pdf'
            ],
            responsive: true,
            pagingType: 'full_numbers',
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt_br.json"
            }
        });
    });
</script>


</body>
</html>
