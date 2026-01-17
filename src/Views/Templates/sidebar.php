<svg xmlns="http://www.w3.org/2000/svg"
    width="60" height="75"
    fill="white"
    class="bi bi-list ms-3 mt-3"
    viewBox="0 0 16 16"
    style="cursor:pointer;"
    data-bs-toggle="offcanvas"
    data-bs-target="#offcanvasWithBothOptions"
    aria-controls="offcanvasWithBothOptions">
    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
</svg>


<div class="offcanvas offcanvas-start bg-dark text-light" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
    <div class="offcanvas-header">
        <div class="d-flex align-items-center text-decoration-none text-light">
            <img src="public/images/logo.svg" alt="Logo" width="32" height="32" class="me-2">
            <span class="fs-5 fw-bold">Pryta</span>
        </div>

        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <hr>
    <div class="offcanvas-body p-0">
        <h3 class="px-3 mb-3">Inicio</h3>

        <a href="index.php?controller=auth&action=home"
            class="list-group-item list-group-item-action bg-dark text-light border-0 ps-4 mb-2">
            Inicio
        </a>

        <a href="index.php?controller=auth&action=profile"
            class="list-group-item list-group-item-action bg-dark text-light border-0 ps-4 mb-2">
            Mi perfil
        </a>

        <hr class="border-light">

        <h3 class="px-3 me-2">Administración</h3>
        <form action="index.php?controller=dashboard&action=list" method="post"
            class="px-3 list-group list-group-flush bg-dark">

            <button type="submit"
                name="table_name"
                value="user"
                class="list-group-item list-group-item-action bg-dark text-light border-0">
                Usuarios
            </button>

            <button type="submit"
                name="table_name"
                value="team"
                class="list-group-item list-group-item-action bg-dark text-light border-0">
                Equipos
            </button>

            <button type="submit"
                name="table_name"
                value="project"
                class="list-group-item list-group-item-action bg-dark text-light border-0">
                Proyectos
            </button>

            <button type="submit"
                name="table_name"
                value="task"
                class="list-group-item list-group-item-action bg-dark text-light border-0">
                Tareas
            </button>
        </form>
        <hr>
        <h3 class="px-3 me-2">Cuenta</h3>
        <div class="list-group list-group-flush bg-dark">
            <a href="index.php?controller=auth&action=logout"
                class="list-group-item list-group-item-action bg-dark text-light border-0 px-3">
                Cerrar sesión
            </a>
        </div>
    </div>

</div>