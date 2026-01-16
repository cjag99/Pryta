<?php
require_once "./src/Model/Entities/UserRole.php";
?>
<html>

<body>
    <div class="card-body">
        <form id="registerForm" action="index.php?controller=dashboard&action=create" method="post">
            <div class="mb-3">
                <label for="reg_username" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="reg_username" name="username" required>
            </div>
            <div class="row g-2">
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="reg_name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="reg_name" name="name" required>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="reg_surname" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="reg_surname" name="surname" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="reg_email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="reg_email" name="email" required>
                <small id="helpRegEmail" class="form-text text-muted">Por favor ingresa un correo válido</small>
            </div>

            <div class="mb-3">
                <label for="reg_role" class="form-label">Rol del usuario</label>
                <select class="form-select" id="reg_role" name="role" required>
                    <option selected disabled value="">Seleccione un rol</option>
                    <?php foreach (UserRole::cases() as $role): ?>
                        <option value="<?= $role->value ?>"><?= $role->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="verified" class="form-label">Verificado:</label><br>
                <input type="radio" name="verified" value="1"> Sí
                <input type="radio" name="verified" value="0" checked> No
            </div>

            <div class="mb-3">
                <label for="active" class="form-label">Activo:</label><br>
                <input type="radio" name="active" value="1" checked> Sí
                <input type="radio" name="active" value="0"> No
            </div>

            <div class="mb-3">
                <label for="reg_password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="reg_password" name="password" required>
            </div>

            <div class="mb-3">
                <label for="reg_confirm" class="form-label">Confirmar contraseña</label>
                <input type="password" class="form-control" id="reg_confirm" name="confirm_password" required>
            </div>

            <button type="submit" class="btn btn-success">Registrar</button>
            <a href="index.php?controller=dashboard&action=list" class="btn btn-danger">Volver</a>
        </form>
    </div>
</body>

</html>