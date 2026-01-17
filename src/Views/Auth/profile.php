<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="public/styles/style.css">
</head>

<?php include_once 'src/Views/Templates/header.php'; ?>
<div class="container my-5">
    <?php
    include_once "src/Utils/alerts.php";
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
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card bg-dark text-light shadow">
                <div class="row g-0 align-items-center">

                    <!-- Avatar -->
                    <div class="col-md-4 text-center p-4 border-end border-light">
                        <img src="public/images/avatar.png" alt="Avatar" class="rounded-circle mb-3" width="120" height="120">
                        <h5 class="mt-2"><?= htmlspecialchars($userInfo->getUsername()) ?></h5>
                        <span class="badge bg-info text-dark"><?= htmlspecialchars($userInfo->getRole()) ?></span>
                    </div>

                    <!-- Información y formulario -->
                    <div class="col-md-8 p-4">

                        <h4 class="mb-3">Información personal</h4>

                        <ul class="list-unstyled mb-4">
                            <li><strong>Nombre:</strong> <?= htmlspecialchars($userInfo->getName()) ?></li>
                            <li><strong>Apellidos:</strong> <?= htmlspecialchars($userInfo->getSurname()) ?></li>
                            <li><strong>Correo:</strong> <?= htmlspecialchars($userInfo->getEmail()) ?></li>
                            <li><strong>Verificado:</strong>
                                <span class="badge <?= $userInfo->isVerified() ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $userInfo->isVerified() ? 'Sí' : 'No' ?>
                                </span>
                            </li>
                            <li><strong>Activo:</strong>
                                <span class="badge <?= $userInfo->isActive() ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $userInfo->isActive() ? 'Sí' : 'No' ?>
                                </span>
                            </li>
                            <li><strong>Pertenece al equipo:</strong> <?= $userInfo->getTeamId() ?? 'N/A' ?></li>
                        </ul>

                        <hr class="border-light">

                        <h5 class="mb-3">Editar perfil</h5>
                        <form action="index.php?controller=auth&action=updateProfile" method="post" class="row g-3">

                            <div class="col-md-6">
                                <label for="username" class="form-label">Usuario</label>
                                <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($userInfo->getUsername()) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($userInfo->getName()) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="surname" class="form-label">Apellidos</label>
                                <input type="text" id="surname" name="surname" class="form-control" value="<?= htmlspecialchars($userInfo->getSurname()) ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Correo</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($userInfo->getEmail()) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" id="password" name="password" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                            </div>
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">Actualizar perfil</button>
                            </div>

                        </form>

                        <div class="col-12 mt-3">

                            <a href="index.php?controller=auth&action=logout" class="btn btn-danger w-100">Cerrar sesión</a>
                        </div>


                    </div> <!-- fin info y formulario -->

                </div> <!-- fin row card -->
            </div> <!-- fin card -->

        </div> <!-- fin col -->
    </div> <!-- fin row -->
</div> <!-- fin container -->
<?php include "./src/Views/Templates/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="public/validate.js"></script>
</body>

</html>