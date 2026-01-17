<div class="card-body">
    <form action="index.php?controller=dashboard&action=create" method="post">
        <div class="mb-3">
            <label for="reg_name" class="form--label">Nombre:</label>
            <input type="text" name="reg_name" class="form-control" required>
        </div>

        <div class="form-floating mb-3">
            <p>Descripción:</p>
            <textarea class="form-control" name="reg_description" id="reg_description"></textarea>
        </div>
        <div class="mb-3">
            <label for="reg_started_at" class="form-label">Fecha de comienzo:</label>
            <input type="date" name="reg_started_at" class="form-control">
        </div>
        <div class="mb-3">
            <label for="reg_due_date" class="form-label">Fecha de entrega:</label>
            <input type="date" name="reg_due_date" class="form-control">
        </div>
        <div class="mb-3">
            <label for="reg_project_id" class="form-label">Proyecto al que pertenece:</label>
            <select name="reg_project_id" class="form-select" required>
                <option value="" selected disabled>Seleccione un proyecto</option>
                <?php foreach ($auxiliar_data[0] as $project): ?>
                    <option value="<?= htmlspecialchars($project['id']) ?>">
                        <?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit" class="btn btn-success">Registrar</button>
        <a href="index.php?controller=dashboard&action=list" class="btn btn-danger">Volver</a>
    </form>
</div>