<?php
function throwAlert()
{
    if (isset($_SESSION['ERROR'])) {
        echo '<div class="alert alert-danger" role="alert">' . $_SESSION['ERROR'] . '</div>';
    }
}
