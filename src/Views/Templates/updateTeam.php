<div class="card-body">
    <form action="index.php?controller=dashboard&action=update" method="post">
        <div class="mb-3">
            <label for="update_name" class="form-label">Nombre</label>
            <input type="text" class="form-control" name="update_name" value=<?= $record['name'] ?> required>
        </div>
        <div class="mb-3">
            <label for="update_description" class="form-label">Descripcion</label>
            <input type="text" class="form-control" name="update_description" value=<?= $record['description'] ?>>
        </div>
        <div class="mb-3">
            <label for="update_creation_date">Fecha de creación</label>
            <input type="date" class="form-control" name="update_creation_date" value=<?= $record['creation_date'] ?> required>
        </div>
        <div class="mb-3">
            <label for="update_team_leader">Lider de equipo</label>
            <select name="update_team_leader">
                <option value="" selected disabled>Seleccione un lider</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="update_is_available">Disponibilidad</label>
            <input type="radio" name="update_is_available" value="1" <?= $record['is_available'] == 1 ? 'checked' : '' ?>>Disponible
            <input type="radio" name="update_is_available" value="0" <?= $record['is_available'] == 0 ? 'checked' : '' ?>>No disponible
        </div>
        <input type="hidden" name="send_id" value=<?= $record['id'] ?>>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="index.php?controller=dashboard&action=list" class="btn btn-danger">Volver</a>
    </form>
</div>