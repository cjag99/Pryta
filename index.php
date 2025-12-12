<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in | Pryta Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="src/styles/style.css">
    <link rel="shortcut icon" href="./src/images/logo.ico" type="image/x-icon">
</head>

<body>
    <div class="loginWindow">
        <div class="container">
            <div class="row">
                <div class="col-6 text-light container-fluid">
                    <br><br>
                    <div class="d-flex flex-row align-items-center">
                        <div>

                            <img src="./src/images/logo.svg" alt="Pryta Logo">
                        </div>
                        <div>
                            <h4 class="text-light m-0">Pryta</h4>
                        </div>
                    </div>
                    <form action="./authentication.php" method="post">
                        <h3>Welcome to Pryta Tech</h3>
                        <div class="mb-3">
                            <label for="user" class="form-label text-light">Please enter your username:</label> <br>
                            <input
                                type="text"
                                class="form-control"
                                name="user"
                                id="user"
                                aria-describedby="helpId"
                                placeholder="User must not be empty"
                                required />
                            <small id="helpId" class="form-text text-muted">Help text</small>
                        </div>
                        <div class="mb-3">
                            <label for="passwd" class="form-label text-light">Please enter your password:</label> <br>
                            <input
                                type="password"
                                class="form-control"
                                name="passwd"
                                id="passwd"
                                aria-describedby="helpId"
                                placeholder="Password must be between 8-16 characters"
                                required />
                            <small id="helpId" class="form-text text-muted">Help text</small>
                        </div>
                        <button
                            type="submit"
                            class="btn btn-primary">
                            Login
                        </button>
                        <button
                            type="reset"
                            class="btn btn-outline-light">
                            Reset
                        </button>

                    </form>
                    <br><br>
                    <div class="failLogin">
                        <?php
                        require_once "./auth_utils.php";
                        throwAlert();
                        ?>
                    </div>
                </div>

                <div class="col-6">
                    <img src="./src/images/ejemplo.jpg" alt="">
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
</body>

</html>