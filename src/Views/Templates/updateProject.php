<div class="card-body">
    <form action="index.php?controller=dashboard&action=update" method="post">
        <div class="mb-3">
            <label for="update_name" class="form--label">Nombre:</label>
            <input type="text" name="update_name" class="form-control" required value=<?= htmlspecialchars($record['name']) ?>>
        </div>

        <div class="form-floating mb-3">
            <p>Descripción:</p>
            <textarea class="form-control" name="update_description" id="update_description">
                <?= htmlspecialchars($record['description']) ?>
            </textarea>
        </div>
        <div class="mb-3">
            <label for="update_started_at" class="form-label">Fecha de comienzo:</label>
            <input type="date" name="update_started_at" class="form-control" value=<?= htmlspecialchars($record['started_at']) ?>>
        </div>
        <div class="mb-3">
            <label for="update_due_date" class="form-label">Fecha de entrega:</label>
            <input type="date" name="update_due_date" class="form-control" value=<?= htmlspecialchars($record['due_date']) ?>>
        </div>
        <div class="mb-3">
            <label for="update_assigned_team" class="form-label">Equipo a cargo:</label>
            <select name="update_assigned_team" class="form-select">
                <option value="" selected disabled>Seleccione una opción</option>
                <?php foreach ($auxiliar_data as $team): ?>
                    <option value="<?= htmlspecialchars($team['id']) ?>"
                        <?= htmlspecialchars($record['assigned_team']) == htmlspecialchars($team['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($team['name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="send_id" value=<?= htmlspecialchars($record['id']) ?>>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="index.php?controller=dashboard&action=list" class="btn btn-danger">Volver</a>
    </form>
</div>