<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajustes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="public/styles/style.css">
</head>

<body>
    <?php include_once 'src/Views/Templates/header.php'; ?>
    <h1 class="text-center text-light">Ajustes</h1>
    <div class="container mt-3">
        <h3 class="text-light">Ajustes del sistema</h3>

        <!-- Preferencias visuales -->
        <div class="card bg-dark text-light mb-3">
            <div class="card-body">
                <h5 class="card-title">Preferencias</h5>
                <form>
                    <div class="mb-3">
                        <label for="theme" class="form-label">Tema</label>
                        <select id="theme" class="form-select">
                            <option value="dark" selected>Oscuro</option>
                            <option value="light">Claro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="items_per_page" class="form-label">Registros por página</label>
                        <input type="number" id="items_per_page" class="form-control" value="10">
                    </div>
                </form>
            </div>
        </div>

        <!-- Opciones del sistema -->
        <div class="card bg-dark text-light mb-3">
            <div class="card-body">
                <h5 class="card-title">Sistema</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item bg-dark border-0">
                        <a href="#" class="text-light text-decoration-none">Ver logs</a>
                    </li>
                    <li class="list-group-item bg-dark border-0">
                        <a href="#" class="text-light text-decoration-none">Configuración avanzada</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Logout -->
        <div class="d-flex justify-content-center mt-3">
            <a class="btn btn-outline-danger d-flex align-items-center"
                href="index.php?controller=auth&action=logout">
                Cerrar sesión
            </a>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>


</body>

</html>