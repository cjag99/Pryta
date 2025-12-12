<?php
session_start();
if (!$_SESSION['LOGGED']) {
    $_SESSION['ERROR'] = "<strong>ERROR:</strong> Access denied. You must login to enter this site";
    header("Location: ./index.php", 0);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <h1><?php echo "Welcome, " . $_SESSION['USER'] ?></h1>

    <a
        name=""
        id=""
        class="btn btn-outline-danger"
        href="./logout.php"
        role="button">Log out</a>

</body>

</html>