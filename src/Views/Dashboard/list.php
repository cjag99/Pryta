<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <a href="index.php?controller=auth&action=home">Volver al inicio</a>
    <h1>Listado de <?= $_SESSION['current_table']; ?></h1>
    <button
        type="button"
        class="btn btn-success btn-lg"
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
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">
                        Añadir nuevo <?= $_SESSION['current_table']; ?>
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php include "./src/Views/Templates/createUser.php"; ?>
                </div>

            </div>
        </div>
    </div>



    <?php if (!empty($data)): ?>
        <?php $columns = array_keys($data[0]); ?>

        <table class="table table-light">
            <thead class="table-dark">
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
                <?php foreach ($data as $user): ?>
                    <?php $row = $user; ?>
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
                                data-bs-target="#updateModal">
                                Editar
                            </button>

                            <!-- Modal Body -->
                            <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                            <div
                                class="modal fade"
                                id="updateModal"
                                tabindex="-1"
                                data-bs-backdrop="static"
                                data-bs-keyboard="false"

                                role="dialog"
                                aria-labelledby="updateModalTitle"
                                aria-hidden="true">
                                <div
                                    class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm"
                                    role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="updateModalTitle">
                                                Editar <?= $_SESSION['current_table']; ?>
                                            </h5>
                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <?php include "./src/Views/Templates/updateUser.php"; ?>
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
                                data-bs-target="#deleteModal">
                                Eliminar
                            </button>

                            <!-- Modal Body -->
                            <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                            <div
                                class="modal fade"
                                id="deleteModal"
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
                                        <form action="index.php?controller=dashboard&action=delete&id=<?= $user['id'] ?>" method="post">
                                            <input type="hidden" name="table_name" value="<?= $_SESSION['current_table'] ?>">
                                            <input type="hidden" name="id" value="<?= $user['id'] ?>">

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
        <p>No hay datos disponibles.</p>
    <?php endif; ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>