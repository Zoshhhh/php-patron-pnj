<?php

require_once __DIR__ . '/config.php';

spl_autoload_register(function ($class) {
    // Convertit le namespace en chemin de fichier
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/src/';

    // Vérifie si la classe utilise le namespace
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Récupère le chemin relatif de la classe
    $relative_class = substr($class, $len);

    // Remplace les séparateurs de namespace par des séparateurs de répertoire
    // Ajoute .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Si le fichier existe, on l'inclut
    if (file_exists($file)) {
        require $file;
    }
}); 