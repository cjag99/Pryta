<?php
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
    <?php include_once 'src/Views/Templates/header.php'; ?>
    <div class="container mt-5">
        <div class="jumbotron bg-dark text-light p-5 rounded shadow-sm">
            <h1 class="display-4">Bienvenido a Pryta, <?= $_SESSION['USER']; ?></h1>
            <p class="lead">
                Pryta es un software de gestión de proyectos pensado para empresas, que permite organizar tareas, equipos y proyectos de manera eficiente.
            </p>
            <hr class="my-4">
            <p>
                Recuerda que solo los usuarios con los permisos adecuados pueden realizar ciertas acciones.
            </p>
            <a class="btn btn-primary btn-lg" href="index.php?controller=auth&action=profile" role="button">
                Ver mi perfil
            </a>
        </div>
    </div>


    <!-- Inicio: pie de página -->
    <?php include_once 'src/Views/Templates/footer.php'; ?>
    <!-- Fin: pie de página -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
</body>

</html>