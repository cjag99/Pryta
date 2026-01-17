<form action="index.php?controller=dashboard&action=update" method="post">
    <div class="mb-3">
        <label for="new_username" class="form-label">Usuario:</label>
        <input type="text" class="form-text" name="new_username" value="<?= htmlspecialchars($record['username']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="new_name" class="form-label">Nombre:</label>
        <input type="text" class="form-text" name="new_name" value="<?= htmlspecialchars($record['name']) ?>" required> <br>
        <label for="new_surname" class="form-label">Apellidos:</label>
        <input type="text" class="form-text" name="new_surname" value="<?= htmlspecialchars($record['surname']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="new_role" class="form-label"> Rol:</label>
        <select class="form-select" name="new_role" required>
            <option selected disabled value="">Seleccione un rol</option>
            <?php foreach (UserRole::cases() as $role): ?>
                <option value="<?= htmlspecialchars($role->value) ?>"
                    <?= htmlspecialchars($record['role']) == htmlspecialchars($role->value) ? 'selected' : '' ?>><?= htmlspecialchars($role->name) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="new_team_id" class="form-label"> Equipo asignado:</label>
        <select class="form-select" name="new_team_id">
            <option selected disabled value="">Seleccione un equipo</option>
            <?php foreach ($auxiliar_data as $team): ?>
                <option value="<?= htmlspecialchars($team['id'], ENT_QUOTES, 'UTF-8') ?>"
                    <?= htmlspecialchars($record['team_id']) == htmlspecialchars($team['id']) ? 'selected' : '' ?>><?= htmlspecialchars($team['name'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="new_email" class="form-label"> Correo:</label>
        <input type="email" class="form-text" name="new_email" value="<?= htmlspecialchars($record['email']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="new_verified" class="form-label">Verificado:</label>
        <input type="radio" name="new_verified" value="1" <?= htmlspecialchars($record['verified']) == '1' ? 'checked' : '' ?>>Sí
        <input type="radio" name="new_verified" value="0" <?= htmlspecialchars($record['verified']) == '0' ? 'checked' : '' ?>>No
    </div>
    <div class="mb-3">
        <label for="new_active" class="form-label">Activo:</label>
        <input type="radio" name="new_active" value="1" <?= htmlspecialchars($record['active']) == '1' ? 'checked' : '' ?>>Sí
        <input type="radio" name="new_active" value="0" <?= htmlspecialchars($record['active']) == '0' ? 'checked' : '' ?>>No
    </div>
    <div class="mb-3">
        <label for="new_password" class="form-label">Contraseña:</label>
        <input type="password" class="form-text" name="new_password">
    </div>
    <div class="mb-3">
        <label for="new_confirm_password" class="form-label">Confirmar contraseña:</label>
        <input type="password" class="form-text" name="new_confirm_password">
    </div>
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <input type="hidden" name="send_id" value="<?= htmlspecialchars($record['id']) ?>">
    <button type="submit" class="btn btn-success">Actualizar</button>
    <a href="index.php?controller=dashboard&action=list" class="btn btn-danger">Volver</a>
</form>