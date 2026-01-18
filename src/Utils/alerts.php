<?php

/**
 * Muestra una alerta de error si existe un mensaje en la sesión.
 *
 * - Usa las clases de Bootstrap `alert alert-danger` para el estilo.
 * - Nota: se asume que el mensaje en `$_SESSION['ERROR']` ya está saneado
 *   antes de almacenarlo para evitar vulnerabilidades XSS.
 */
function throwErrorAlert()
{
    // Si hay un error en sesión, lo mostramos dentro de un div con clase alert
    if (isset($_SESSION['ERROR'])) {
        echo '<div class="alert alert-danger fade show" role="alert" >' . $_SESSION['ERROR'] . '</div>';
    }
}

/**
 * Muestra una alerta de éxito si existe un mensaje en la sesión.
 *
 * - Usa las clases de Bootstrap `alert alert-success` para el estilo.
 * - Nota: se asume que el mensaje en `$_SESSION['SUCCESS']` ya está saneado
 *   antes de almacenarlo para evitar vulnerabilidades XSS.
 */
function throwCreateAlert()
{
    // Si hay una inserción correcta en sesión, lo mostramos dentro de un div con clase alert
    if (isset($_SESSION['SUCCESS'])) {
        echo '<div class="alert alert-success fade show" role="alert" >' . $_SESSION['SUCCESS'] . '</div>';
    }
}

/**
 * Muestra una alerta de actualización si existe un mensaje en la sesión.
 *
 * - Usa las clases de Bootstrap `alert alert-info` para el estilo.
 * - Nota: se asume que el mensaje en `$_SESSION['INFO']` ya está saneado
 *   antes de almacenarlo para evitar vulnerabilidades XSS.
 */
function throwUpdateAlert()
{
    // Si hay un update correcto en sesión, lo mostramos dentro de un div con clase alert
    if (isset($_SESSION['INFO'])) {
        echo '<div class="alert alert-info fade show" role="alert" >' . $_SESSION['INFO'] . '</div>';
    }
}
