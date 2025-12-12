<?php
session_start();
$_SESSION['USER'] = $_POST['user'];
$_SESSION['PASSWD'] = $_POST['passwd'];
$_SESSION['SERVER'] = 'mysql:host=localhost;dbname=pryta';

try {
    $pdo = new PDO($_SESSION['SERVER'], $_SESSION['USER'], $_SESSION['PASSWD']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $_SESSION['LOGGED'] = true;
    header('Location: ./home.php', 0);
} catch (PDOException $error) {
    $_SESSION['ERROR'] = "<strong>ERROR:</strong> Access denied for user " . $_SESSION['USER'] . " (using password: YES)";
    header('Location: ./index.php', 0);
}
