<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Óbitos</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <style>
        table {
            font-size: 0.9rem;
        }
        th, td {
            padding: 0.5rem;
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Lista de Pessoas com Óbito Registrado</h1>
        
        <table id="obitosTable" class="table table-striped table-bordered table-sm">
            <thead class="thead-dark">
                <tr>
                    <th>Nome do Cidadão</th>
                    <th>Nome da Mãe</th>
                    <th>Data de Nascimento</th>
                    <th>Data do Óbito</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($obitos as $obito)
                    <tr>
                        <td>{{ $obito->no_cidadao }}</td>
                        <td>{{ $obito->no_mae }}</td>
                        <td>{{ $obito->dt_nascimento }}</td>
                        <td>{{ $obito->dt_obito_sim }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-center mt-4">
            <a href="{{ url()->previous() }}" class="btn btn-primary">Voltar</a>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
    $(document).ready(function() {
        $('#obitosTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'pdf'
            ],
            language: {
                sEmptyTable: "Nenhum dado disponível na tabela",
                sInfo: "Mostrando de _START_ até _END_ de _TOTAL_ entradas",
                sInfoEmpty: "Mostrando 0 até 0 de 0 entradas",
                sInfoFiltered: "(filtrado de _MAX_ entradas no total)",
                sLengthMenu: "Mostrar _MENU_ entradas",
                sLoadingRecords: "Carregando...",
                sProcessing: "Processando...",
                sSearch: "Pesquisar:",
                sZeroRecords: "Nenhum resultado encontrado",
                oPaginate: {
                    sFirst: "Primeira",
                    sLast: "Última",
                    sNext: "Próxima",
                    sPrevious: "Anterior"
                }
            }
        });
    });
</script>


    <footer class="text-center mt-5">
        <p>&copy; {{ date('Y') }} - Projeto-Águia</p>
    </footer>
</body>
</html>
