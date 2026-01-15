<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | Pryta Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="public/styles/style.css">
    <link rel="shortcut icon" href="public/images/logo.ico" type="image/x-icon">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="loginWindow">
        <div class="container">
            <div class="row align-items-center g-0">
                <div class="col-12 col-md-7 text-light d-flex align-items-center order-2 order-md-1 h-100">
                    <div class="w-100 px-4 py-5" style="max-width:560px; margin:auto;">
                        <!-- Inicio: logo y título de la aplicación -->
                        <div class="d-flex align-items-center mb-4">
                            <img src="public/images/logo.svg" alt="Pryta Logo" class="me-2" style="max-height:40px;">
                            <h4 class="text-light m-0">Pryta</h4>
                        </div>
                        <!-- Fin: logo y título --> <!-- Fin: logo y título -->

                        <!-- Inicio: formulario de inicio de sesión -->
                        <form id="loginForm" action="?controller=auth&action=authenticate" method="post" onsubmit="validateLogin(event)">
                            <h3 class="mb-4">Bienvenido a Pryta Tech</h3>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="username" class="form-label text-light">Por favor, ingresa tu usuario:</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="username"
                                            id="username"
                                            aria-describedby="helpUsername"
                                            placeholder="Usuario (6-15 caracteres)"
                                            required />
                                        <small id="helpUsername" class="form-text text-muted">El usuario debe tener entre 6 y 15 caracteres</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="password" class="form-label text-light">Por favor, ingresa tu contraseña:</label>
                                        <input
                                            type="password"
                                            class="form-control"
                                            name="password"
                                            id="password"
                                            aria-describedby="helpPassword"
                                            placeholder="Contraseña (8-16 caracteres)"
                                            required />
                                        <small id="helpPassword" class="form-text text-muted">La contraseña debe tener entre 8 y 16 caracteres</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex flex-column flex-sm-row gap-2">
                                        <button
                                            type="submit"
                                            class="btn btn-primary flex-fill"
                                            id="loginBtn">
                                            Iniciar sesión
                                        </button>
                                        <button
                                            type="reset"
                                            class="btn btn-outline-light flex-fill">
                                            Restablecer
                                        </button>
                                    </div>
                                    <div class="text-center mt-3">
                                        <small class="text-light">¿No tienes cuenta? <a href="index.php?action=register" class="link-light fw-bold">Regístrate</a></small>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- Fin: formulario de inicio de sesión -->

                        <!-- Inicio: zona de mensajes de error -->
                        <div class="mt-3 failLogin">
                            <?php
                            require_once "./src/Utils/auth_utils.php";
                            if (isset($_SESSION['ERROR'])) {
                                throwAlert();
                                unset($_SESSION['ERROR']);
                            }

                            ?>
                        </div>
                        <!-- Fin: zona de mensajes de error -->
                    </div>
                </div>

                <!-- Columna de imagen (oculta en pantallas pequeñas) -->
                <div class="d-none d-md-block col-md-5 order-1 order-md-2 p-0 h-100">
                    <img src="public/images/portada.png" alt="" class="img-fluid w-100 h-100 mt-5">
                </div>
            </div>
        </div>
    </div>

    <!-- Inicio: pie de página -->
    <footer class="bg-dark text-light py-2 mt-auto">
        <div class="container text-center small">
            &copy; 2025 Pryta Tech. Todos los derechos reservados.
        </div>
    </footer>
    <!-- Fin: pie de página -->

    <!-- Scripts: librerías y validación del lado cliente -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
    <script src="public/validate.js"></script>
</body>

</html>