<?php
require_once __DIR__ . '/config.php';
require_once ROOT_PATH . '/vendor/autoload.php';

// Rediriger vers la page d'index des personnages
header('Location: public/views/personnage/index.php');
exit; 