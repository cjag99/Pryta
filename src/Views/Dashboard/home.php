<?php
// La sesión se inicia en index.php; no la iniciamos de nuevo aquí.
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) {
    // Mensaje de error en caso de acceso no autorizado (traducido a español)
    $_SESSION['ERROR'] = "<strong>ERROR:</strong> Acceso denegado. Debes iniciar sesión para entrar en el sitio";
    header("Location: index.php?controller=auth&action=login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="public/styles/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Inicio: contenido principal -->
    <main class="flex-fill">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <!-- Inicio: tarjeta del panel -->
                    <div class="card bg-dark text-light border-0 shadow-sm">
                        <!-- Cabecera del panel -->
                        <div class="card-header bg-transparent border-0 text-light">
                            <h5 class="mb-0">Panel</h5>
                        </div>
                        <!-- Cuerpo del panel -->
                        <div class="card-body">
                            <?php
                            $displayName = isset($_SESSION['USER']) ? htmlspecialchars($_SESSION['USER']) : 'User #' . intval($_SESSION['user_id']);
                            ?>
                            <h4 class="card-title mb-2">Bienvenido, <?= $displayName ?></h4>

                            <!-- Lista de proyectos asignados -->
                            <p class="mb-1 fw-semibold">Proyectos asignados:</p>

                            <ul class="list-group mb-3">
                                <li class="list-group-item bg-transparent text-light border-secondary">No hay proyectos asignados.</li>
                            </ul>

                            <!-- Botón de cerrar sesión -->
                            <div class="d-flex justify-content-center">
                                <a class="btn btn-danger" href="index.php?controller=auth&action=logout" role="button">Cerrar sesión</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Inicio: pie de página -->
    <footer class="bg-dark text-light py-2 mt-auto">
        <div class="container text-center small">&copy; 2025 Pryta Tech. Todos los derechos reservados.</div>
    </footer>
    <!-- Fin: pie de página -->
</body>

</html>