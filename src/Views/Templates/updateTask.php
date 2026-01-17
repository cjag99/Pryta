<?php
require_once "./src/Model/Entities/TaskState.php";
?>
<div class="card-body">
    <form action="index.php?controller=dashboard&action=update" method="post">
        <?php if (!AuthService::hasRole("Software Engineer")): ?>
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
                <label for="update_started_on" class="form-label">Fecha de comienzo:</label>
                <input type="date" name="update_started_on" class="form-control" value=<?= htmlspecialchars($record['started_on']) ?>>
            </div>
            <div class="mb-3">
                <label for="update_due_date" class="form-label">Fecha de entrega:</label>
                <input type="date" name="update_due_date" class="form-control" value=<?= htmlspecialchars($record['due_date']) ?>>
            </div>
            <div class="mb-3">
                <label for="update_project_id" class="form-label">Proyecto al que pertenece:</label>
                <select name="update_project_id" class="form-select" required>
                    <option value="" selected disabled>Seleccione un proyecto</option>
                    <?php foreach ($auxiliar_data[0] as $project): ?>
                        <option value="<?= htmlspecialchars($project['id']) ?>"
                            <?= htmlspecialchars($record['project_id']) == htmlspecialchars($project['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="update_member_assigned">Miembro asignado:</label>
                <select name="update_member_assigned" class="form-select">
                    <option value="" selected disabled>Seleccione un miembro</option>
                    <?php foreach ($auxiliar_data[1] as $member): ?>
                        <option value="<?= htmlspecialchars($member['id']) ?>">
                            <? htmlspecialchars($member['id']) === htmlspecialchars($record['id']) ? 'selected' : '' ?>
                            <?= htmlspecialchars($member['name'] . ' ' . $member['surname'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
        <div class="mb-3">
            <label for="update_state" class="form-label">Estado:</label>
            <select name="update_state" class="form-select" required>
                <option value="" disabled>Seleccione un estado</option>
                <?php foreach (TaskState::cases() as $state): ?>
                    <option value="<?= htmlspecialchars($state->value) ?>"
                        <?= htmlspecialchars($record['state']) == htmlspecialchars($state->value) ? 'selected' : '' ?>><?= $state->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="send_id" value=<?= htmlspecialchars($record['id']) ?>>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="index.php?controller=dashboard&action=list" class="btn btn-danger">Volver</a>
    </form>
</div>