<?php
session_start();

if (isset($_GET['id']) && isset($_SESSION['items'][$_GET['id']])) {
    unset($_SESSION['items'][$_GET['id']]);
    $_SESSION['items'] = array_values($_SESSION['items']); 
    $_SESSION['success'] = 'Item supprimé avec succès';
} else {
    $_SESSION['error'] = 'Item introuvable';
}

header('Location: /views/item/show.php');
exit; 