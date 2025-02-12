<!-- resources/views/obitos/base.blade.php -->

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificações Sinan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles') <!-- Para adicionar estilos específicos em algumas páginas -->
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Notificações Sinan</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('obitos.notificacoes.index') }}">Notificações</a>
                    </li>
                    <!-- Adicione outros links aqui -->
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content') <!-- Aqui as views específicas irão injetar seu conteúdo -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts') <!-- Para adicionar scripts específicos em algumas páginas -->
    <footer>
        <p>&copy; {{ date('Y') }} - Projeto-aguia</p>
    </footer>
</body>

</html>
