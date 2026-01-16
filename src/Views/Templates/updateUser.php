<form action="index.php?controller=dashboard&action=update" method="post">
    <div class="mb-3">
        <label for="new_username" class="form-label">Usuario:</label>
        <input type="text" class="form-text" name="new_username" value=<?= $record['username'] ?> required>
    </div>

    <div class="mb-3">
        <label for="new_name" class="form-label">Nombre:</label>
        <input type="text" class="form-text" name="new_name" value=<?= $record['name'] ?> required> <br>
        <label for="new_surname" class="form-label">Apellidos:</label>
        <input type="text" class="form-text" name="new_surname" value=<?= $record['surname'] ?> required>
    </div>
    <div class="mb-3">
        <label for="new_role" class="form-label"> Rol:</label>
        <select class="form-select" name="new_role" required>
            <option selected disabled value="">Seleccione un rol</option>
            <?php foreach (UserRole::cases() as $role): ?>
                <option value="<?= $role->value ?>"
                    <?= $record['role'] == $role->value ? 'selected' : '' ?>><?= $role->name ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="new_email" class="form-label"> Correo:</label>
        <input type="email" class="form-text" name="new_email" value=<?= $record['email'] ?> required>
    </div>
    <div class="mb-3">
        <label for="new_verified" class="form-label">Verificado:</label>
        <input type="radio" name="new_verified" value="1" <?= $record['verified'] == 1 ? 'checked' : '' ?>>Sí
        <input type="radio" name="new_verified" value="0" <?= $record['verified'] == 0 ? 'checked' : '' ?>>No
    </div>
    <div class="mb-3">
        <label for="new_active" class="form-label">Activo:</label>
        <input type="radio" name="new_active" value="1" <?= $record['active'] == 1 ? 'checked' : '' ?>>Sí
        <input type="radio" name="new_active" value="0" <?= $record['active'] == 0 ? 'checked' : '' ?>>No
    </div>
    <div class="mb-3">
        <label for="new_teamId" class="form-label">Equipo:</label>
        <select class="form-select" name="new_teamId">
            <option selected disabled value="">Seleccione un equipo</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="new_password" class="form-label">Contraseña:</label>
        <input type="password" class="form-text" name="new_password">
    </div>
    <div class="mb-3">
        <label for="new_confirm_password" class="form-label">Confirmar contraseña:</label>
        <input type="password" class="form-text" name="new_confirm_password">
    </div>
    <input type="hidden" name="send_id" value="<?= $record['id'] ?>">
    <button type="submit" class="btn btn-success">Actualizar</button>
    <a href="index.php?controller=dashboard&action=list" class="btn btn-danger">Volver</a>
</form>