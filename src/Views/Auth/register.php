<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | Pryta Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="public/styles/style.css">
    <link rel="shortcut icon" href="public/images/logo.ico" type="image/x-icon">
</head>

<body class="d-flex flex-column min-vh-100">
    <main class="flex-fill">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <!-- Inicio: logo y título -->
                    <div class="d-flex align-items-center justify-content-center mb-4">
                        <img src="public/images/logo.svg" alt="Pryta Logo" class="me-2" style="max-height:40px;">
                        <h4 class="text-light m-0">Pryta</h4>
                    </div>
                    <!-- Fin: logo y título -->
                    <!-- Inicio: tarjeta de registro -->
                    <div class="card border-0 shadow-sm" style="background-color: rgba(255,255,255,0.03);">
                        <!-- Cabecera de la tarjeta -->
                        <div class="card-header bg-transparent border-0">
                            <h5 class="mb-0 text-light">Crear cuenta</h5>
                        </div>
                        <!-- Cuerpo de la tarjeta (formulario) -->
                        <div class="card-body">
                            <!-- Inicio: formulario de registro -->
                            <form id="registerForm" action="?controller=auth&action=processRegistration" method="post" novalidate onsubmit="return validateRegister(event)">
                                <div class="mb-3">
                                    <label for="reg_username" class="form-label text-light">Usuario</label>
                                    <input type="text" class="form-control" id="reg_username" name="username" aria-describedby="helpRegUsername" required>
                                    <small id="helpRegUsername" class="form-text text-muted">El usuario debe tener entre 6 y 15 caracteres</small>
                                </div>

                                <div class="row g-2">
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="reg_name" class="form-label text-light">Nombre</label>
                                            <input type="text" class="form-control" id="reg_name" name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="reg_surname" class="form-label text-light">Apellidos</label>
                                            <input type="text" class="form-control" id="reg_surname" name="surname" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="reg_email" class="form-label text-light">Correo electrónico</label>
                                    <input type="email" class="form-control" id="reg_email" name="email" required>
                                    <small id="helpRegEmail" class="form-text text-muted">Por favor ingresa un correo válido</small>
                                </div>

                                <div class="mb-3">
                                    <label for="reg_password" class="form-label text-light">Contraseña</label>
                                    <input type="password" class="form-control" id="reg_password" name="password" aria-describedby="helpRegPassword" required>
                                    <small id="helpRegPassword" class="form-text text-muted">La contraseña debe tener entre 8 y 16 caracteres</small>
                                </div>

                                <div class="mb-3">
                                    <label for="reg_confirm" class="form-label text-light">Confirmar contraseña</label>
                                    <input type="password" class="form-control" id="reg_confirm" name="confirm_password" required>
                                    <small id="helpRegConfirm" class="form-text text-muted">La confirmación debe coincidir con la contraseña</small>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">Registrarse</button>
                                    <button type="reset" class="btn btn-outline-light flex-fill">Restablecer</button>
                                </div>
                            </form>
                            <!-- Fin: formulario de registro -->

                            <!-- Enlace a iniciar sesión -->
                            <div class="text-center mt-3">
                                <small class="text-light">¿Ya tienes cuenta? <a href="index.php?action=login" class="link-light fw-bold">Inicia sesión</a></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Inicio: pie de página -->
    <footer class="bg-dark text-light py-2 mt-auto">
        <div class="container text-center small">&copy; 2025 Pryta Tech. Todos los derechos reservados.</div>
    </footer>
    <!-- Fin: pie de página -->

    <!-- Scripts: librerías y validación del lado cliente -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
    <script src="public/validate.js"></script>
</body>

</html>