<?php

/**
 * Muestra una alerta de error si existe un mensaje en la sesión.
 *
 * - Usa las clases de Bootstrap `alert alert-danger` para el estilo.
 * - Nota: se asume que el mensaje en `$_SESSION['ERROR']` ya está saneado
 *   antes de almacenarlo para evitar vulnerabilidades XSS.
 */
function throwAlert()
{
    // Si hay un error en sesión, lo mostramos dentro de un div con clase alert
    if (isset($_SESSION['ERROR'])) {
        echo '<div class="alert alert-danger fade show" role="alert" >' . $_SESSION['ERROR'] . '</div>';
    }
}
