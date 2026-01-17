<div class="card-body">
    <form action="index.php?controller=dashboard&action=create" method="post">
        <div class="mb-3">
            <label for="reg_name" class="form-label">Nombre</label>
            <input type="text" class="form-control" name="reg_name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="reg_description" class="form-label">Descripcion</label>
            <input type="text" class="form-control" name="reg_description" name="description">
        </div>
        <div class="mb-3">
            <label for="reg_creation_date">Fecha de creación</label>
            <input type="date" class="form-control" name="reg_creation_date" name="creation_date" required>
        </div>
        <div class="mb-3">
            <label for="reg_team_leader">Lider de equipo</label>
            <select name="reg_team_leader">
                <option value="" selected disabled>Seleccione un lider</option>
                <?php foreach ($auxiliar_data as $user): ?>
                    <option value="<?= htmlspecialchars($user['id']) ?>">
                        <?= htmlspecialchars($user['name'] . ' ' . $user['surname'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="reg_is_available">Disponibilidad</label>
            <input type="radio" name="reg_is_available" value="1" checked>Disponible
            <input type="radio" name="reg_is_available" value="0">No disponible
        </div>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit" class="btn btn-success">Registrar</button>
        <a href="index.php?controller=dashboard&action=list" class="btn btn-danger">Volver</a>
    </form>
</div>