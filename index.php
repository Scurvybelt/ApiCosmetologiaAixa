<?php

// session_start();
// include 'servicesController.php';
//include 'loginController.php';
$requestUri = $_SERVER['REQUEST_URI'];

$requestUri = preg_split('/[?&]/', $requestUri)[0];
$segments = explode('/', trim($requestUri, '/'));


// Dividir la ruta en segmentos
$segments = explode('/', trim($requestUri, '/'));

// Verificar el primer segmento después del localhost
if (isset($segments[2]) && $segments[2] === 'services') {
    include 'servicesController.php';
} elseif (isset($segments[2]) && $segments[2] === 'login') {
    include 'loginController.php';
} else {
    // Ruta por defecto o manejar error 404
    echo "Página no encontrada.";
}

?>