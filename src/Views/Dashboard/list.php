<?php
require_once "./src/Services/AuthService.php";
if (!isset($_SESSION['user_id'])) {
    // Mensaje de error en caso de acceso no autorizado (traducido a español)
    $_SESSION['ERROR'] = "<strong>ERROR:</strong> Acceso denegado. Debes iniciar sesión para entrar en el sitio";
    header("Location: index.php?controller=auth&action=login");
    exit();
}
$nombreTabla = match ($_SESSION['current_table']) {
    'user' => 'Usuarios',
    'team' => 'Equipos',
    'project' => 'Proyectos',
    'task' => 'Tareas',
};
$canSeeCreateButton = $_SESSION['role'] === 'Superadmin' || ($_SESSION['role'] === 'Team Leader' && $_SESSION['current_table'] === 'task');
$canSeeUpdateButton = AuthService::hasRole('Superadmin') || $_SESSION['current_table'] === 'task';
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

<body>
    <?php include_once "src/Views/Templates/header.php" ?>
    <h1 class="text-center text-light"><?= $nombreTabla; ?></h1>
    <div class="container table-responsive">
        <!-- Verificar rol para mostrar botón de creación -->
        <?php if ($canSeeCreateButton) : ?>
            <button
                type="button"
                class="btn btn-success btn-md m-4"
                data-bs-toggle="modal"
                data-bs-target="#modalId">
                Crear
            </button>
        <?php endif; ?>
        <div
            class="modal fade"
            id="modalId"
            tabindex="-1"
            data-bs-backdrop="static"
            data-bs-keyboard="false"

            role="dialog"
            aria-labelledby="modalTitleId"
            aria-hidden="true">
            <div
                class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm"
                role="document">
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">
                            Añadir nuevo <?= $_SESSION['current_table']; ?>
                        </h5>
                        <button
                            type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php
                        switch ($_SESSION['current_table']) {
                            case 'user':
                                include "./src/Views/Templates/createUser.php";
                                break;
                            case 'team':
                                include "./src/Views/Templates/createTeam.php";
                                break;
                            case 'project':
                                include "./src/Views/Templates/createProject.php";
                                break;
                            case 'task':
                                include "./src/Views/Templates/createTask.php";
                                break;
                            default:
                                $_SESSION['ERROR'] = "<strong>ERROR: </strong>Esta tabla no existe en la base de datos.";
                                header("index.php?controller=auth&action=logout");
                                exit;
                        }
                        ?>
                    </div>

                </div>
            </div>
        </div>



        <?php if (!empty($data)): ?>
            <?php $columns = array_keys($data[0]); ?>

            <table class="table table-striped table-hover table-dark shadow-lg rounded-3">
                <thead>
                    <tr>
                        <?php foreach ($columns as $col): ?>
                            <?php if ($col !== 'passwd'): ?>
                                <th><?= htmlspecialchars($col) ?></th>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $record): ?>
                        <?php $row = $record; ?>
                        <tr>
                            <?php foreach ($columns as $col): ?>
                                <?php if ($col !== 'passwd'): ?>
                                    <td><?= htmlspecialchars((string)($row[$col] ?? '')) ?></td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <td>
                                <?php if ($canSeeUpdateButton) : ?>
                                    <button
                                        type="button"
                                        class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#updateModal<?= $record['id']  ?>">
                                        Editar
                                    </button>
                                <?php endif; ?>
                                <div
                                    class="modal fade"
                                    id="updateModal<?= $record['id']  ?>"
                                    tabindex="-1"
                                    data-bs-backdrop="static"
                                    data-bs-keyboard="false"

                                    role="dialog"
                                    aria-labelledby="updateModalTitle"
                                    aria-hidden="true">
                                    <div
                                        class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm"
                                        role="document">
                                        <div class="modal-content bg-dark text-light">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="updateModalTitle">
                                                    Editar <?= $_SESSION['current_table']; ?>
                                                </h5>
                                                <button
                                                    type="button"
                                                    class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <?php
                                                switch ($_SESSION['current_table']) {
                                                    case 'user':
                                                        include "./src/Views/Templates/updateUser.php";
                                                        break;
                                                    case 'team':
                                                        include "./src/Views/Templates/updateTeam.php";
                                                        break;
                                                    case 'project':
                                                        include "./src/Views/Templates/updateProject.php";
                                                        break;
                                                    case 'task':
                                                        include "./src/Views/Templates/updateTask.php";
                                                        break;
                                                    default:
                                                        $_SESSION['ERROR'] = "<strong>ERROR: </strong>Esta tabla no existe en la base de datos.";
                                                        header("index.php?controller=auth&action=logout");
                                                        exit;
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php if (AuthService::hasRole('Superadmin')): ?>
                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal<?= $record['id']  ?>">
                                        Eliminar
                                    </button>
                                <?php endif; ?>

                                <div
                                    class="modal fade"
                                    id="deleteModal<?= $record['id']  ?>"
                                    tabindex="-1"
                                    data-bs-backdrop="static"
                                    data-bs-keyboard="false"

                                    role="dialog"
                                    aria-labelledby="deleteModalTitle"
                                    aria-hidden="true">
                                    <div
                                        class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm"
                                        role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalTitle">
                                                    Eliminar registro
                                                </h5>
                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">¿Desea eliminar el registro?</div>
                                            <form action="index.php?controller=dashboard&action=delete&id=<?= htmlspecialchars($record['id']) ?>" method="post">
                                                <input type="hidden" name="table_name" value="<?= $_SESSION['current_table'] ?>">
                                                <input type="hidden" name="id" value="<?= htmlspecialchars($record['id']) ?>">
                                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                <div class="modal-footer">
                                                    <button
                                                        type="button"
                                                        class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        Cancelar
                                                    </button>
                                                    <button type="submit" class="btn btn-danger">Confirmar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        <?php else: ?>
            <div class="d-flex flex-column align-items-center justify-content-center bg-dark text-light border border-light rounded p-3 my-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                    <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                </svg>
                <p class="mb-0">No hay registros disponibles</p>
            </div>


        <?php endif; ?>
        <?php
        require_once "./src/Utils/alerts.php";
        if (isset($_SESSION['ERROR'])) {
            throwErrorAlert();
            unset($_SESSION['ERROR']);
        }
        if (isset($_SESSION['SUCCESS'])) {
            throwCreateAlert();
            unset($_SESSION['SUCCESS']);
        }
        if (isset($_SESSION['INFO'])) {
            throwUpdateAlert();
            unset($_SESSION['INFO']);
        }
        ?>
    </div>

    <?php include "./src/Views/Templates/footer.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/validate.js"></script>

</body>

</html>