<?php
$nombreTabla = match ($_SESSION['current_table']) {
    'user' => 'Usuarios',
    'team' => 'Equipos',
    'project' => 'Proyectos',
    'task' => 'Tareas',
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

<body>
    <?php include_once "src/Views/Templates/header.php" ?>
    <h1 class="text-center text-light"><?= $nombreTabla; ?></h1>
    <div class="container">

        <button
            type="button"
            class="btn btn-success btn-md m-4"
            data-bs-toggle="modal"
            data-bs-target="#modalId">
            Crear
        </button>

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
                        }
                        ?>
                    </div>

                </div>
            </div>
        </div>



        <?php if (!empty($data)): ?>
            <?php $columns = array_keys($data[0]); ?>

            <table class="table table-striped table-hover table-dark">
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
                                <!-- Modal trigger button -->
                                <button
                                    type="button"
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#updateModal<?= $record['id']  ?>">
                                    Editar
                                </button>

                                <!-- Modal Body -->
                                <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
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
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Optional: Place to the bottom of scripts -->


                                <!-- Modal trigger button -->
                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal<?= $record['id']  ?>">
                                    Eliminar
                                </button>

                                <!-- Modal Body -->
                                <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
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
                                            <form action="index.php?controller=dashboard&action=delete&id=<?= $record['id'] ?>" method="post">
                                                <input type="hidden" name="table_name" value="<?= $_SESSION['current_table'] ?>">
                                                <input type="hidden" name="id" value="<?= $record['id'] ?>">

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
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>