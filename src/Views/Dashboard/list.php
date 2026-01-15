<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>

<body>
    <h1>Listado de <?php echo $_SESSION['current_table']; ?></h1>
    <?php if (empty($data)): ?>
        <p>No hay datos en la base de datos</p>
    <?php else: ?>
        <table border=1>
            <thead>
                <tr>
                    <?php foreach (array_keys($data[0]) as $field): ?>
                        <th>
                            <?= htmlspecialchars($field); ?>
                        </th>
                    <?php endforeach; ?>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <?php foreach ($row as $value): ?>
                            <td><?= htmlspecialchars($value) ?></td>
                        <?php endforeach; ?>
                        <td>
                            <a href="index.php?controller=dashboard&action=edit&id=<?= $row['id'] ?>">Editar</a>
                            <a href="index.php?controller=dashboard&action=delete&id=<?= $row['id'] ?>">Borrar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>

</html>