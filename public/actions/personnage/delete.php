<?php
session_start();

if (isset($_GET['id'])) {
    $index = (int)$_GET['id'];
    if (isset($_SESSION['personnages'][$index])) {
        unset($_SESSION['personnages'][$index]);
        $_SESSION['personnages'] = array_values($_SESSION['personnages']);
    }
}

header('Location: /views/personnage/index.php');
exit; 