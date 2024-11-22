<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('content-type: application/json; charset=utf-8');
require 'servicesModel.php';

$servicesModel= new servicesModel();

$requestUri = $_SERVER['REQUEST_URI'];
// $endpoint = explode('/', trim($requestUri, '/'))[3]; 

switch($_SERVER['REQUEST_METHOD']){    
    case 'GET':
        
        if (isset($_GET['id']) && isset($_GET['category'])) {
            $respuesta = $servicesModel->getServicesByIdAndCategory($_GET['id'], $_GET['category']);
        } else if (isset($_GET['name']) && isset($_GET['category'])) {
            $respuesta = $servicesModel->getServicesByNameAndCategory($_GET['name'], $_GET['category']);
        } else if (isset($_GET['name'])) {
            $respuesta = $servicesModel->getServicesByName($_GET['name']);
        } else if (isset($_GET['category'])) {
            $respuesta = $servicesModel->getServicesByCategory($_GET['category']);
        } else {
            $respuesta = (!isset($_GET['id'])) ? $servicesModel->getServices() : $servicesModel->getServicesById($_GET['id']);
        }
        echo json_encode($respuesta);
    break;

    case 'POST':
        $_POST = json_decode(file_get_contents('php://input', true));

        if (strpos($requestUri, 'email') !== false) {
            $respuesta = $servicesModel->sendEmail($_POST->asunto, $_POST->email, $_POST->message, $_POST->name, $_POST->tel);
        } else {
            if (!isset($_POST->name) || is_null($_POST->name) || empty(trim($_POST->name)) || strlen($_POST->name) > 60) {
                $respuesta = ['error', 'El nombre del producto no debe estar vacío y no debe de tener más de 60 caracteres'];
            } else if (!isset($_POST->category) || is_null($_POST->category) || empty(trim($_POST->category))) {
                $respuesta = ['error', 'La categoría del producto no debe estar vacía'];
            } else if (!isset($_POST->price) || is_null($_POST->price) || empty(trim($_POST->price)) || !is_numeric($_POST->price) || strlen($_POST->price) > 10) {
                $respuesta = ['error', 'El precio del producto no debe estar vacío, debe ser de tipo numérico y no tener más de 10 caracteres'];
            } else if (!isset($_POST->description) || is_null($_POST->description) || empty(trim($_POST->description)) || strlen($_POST->description) > 500) {
                $respuesta = ['error', 'La descripción del producto no debe estar vacía y no debe de tener más de 500 caracteres'];
            } else if (!isset($_POST->information) || is_null($_POST->information) || empty(trim($_POST->information || strlen($_POST->description) > 500))) {
                $respuesta = ['error', 'La información del producto no debe estar vacía y no debe de tener más de 500 caracteres'];
            } else {
                $respuesta = $servicesModel->saveServices($_POST->name, $_POST->category, $_POST->price, $_POST->description, $_POST->information);
            }
        }
        echo json_encode($respuesta);
    break;

    case 'PUT':
        $_PUT = json_decode(file_get_contents('php://input', true));
        if (!isset($_PUT->id) || is_null($_PUT->id) || empty(trim($_PUT->id))) {
            $respuesta = ['error', 'El ID del producto no debe estar vacío'];
        } else if (!isset($_PUT->name) || is_null($_PUT->name) || empty(trim($_PUT->name)) || strlen($_PUT->name) > 80) {
            $respuesta = ['error', 'El nombre del producto no debe estar vacío y no debe de tener más de 80 caracteres'];
        } else if (!isset($_PUT->category) || is_null($_PUT->category) || empty(trim($_PUT->category))) {
            $respuesta = ['error', 'La categoría del producto no debe estar vacía'];
        } else if (!isset($_PUT->price) || is_null($_PUT->price) || empty(trim($_PUT->price)) || !is_numeric($_PUT->price) || strlen($_PUT->price) > 20) {
            $respuesta = ['error', 'El precio del producto no debe estar vacío, debe ser de tipo numérico y no tener más de 20 caracteres'];
        } else if (!isset($_PUT->description) || is_null($_PUT->description) || empty(trim($_PUT->description)) || strlen($_PUT->description) > 500) {
            $respuesta = ['error', 'La descripción breve del producto no debe estar vacía y no debe de tener más de 500 caracteres'];
        } else if (!isset($_PUT->information) || is_null($_PUT->information) || empty(trim($_PUT->information || strlen($_PUT->description) > 500))) {
            $respuesta = ['error', 'La información detallada del producto no debe estar vacía y no debe de tener más de 500 caracteres'];
        } else {
            $respuesta = $servicesModel->updateServices($_PUT->id, $_PUT->name, $_PUT->category, $_PUT->price, $_PUT->description, $_PUT->information);
        }
        echo json_encode($respuesta);
    break;

    case 'DELETE':
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
    
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }
    
        $input = file_get_contents('php://input');
        
        $_DELETE = json_decode($input, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $respuesta = ['error', 'Error al decodificar los datos de entrada: ' . json_last_error_msg()];
        } else {
            if (!isset($_DELETE['id']) || is_null($_DELETE['id']) || empty(trim($_DELETE['id']))) {
                $respuesta = ['error', 'El ID del servicio no debe estar vacío'];
            } else {
                try {
                    $respuesta = $servicesModel->deleteServices($_DELETE['id']);
                    
                    if (empty($respuesta)) {
                        $respuesta = ['success', 'Servicio eliminado correctamente'];
                    }
                } catch (Exception $e) {
                    $respuesta = ['error', 'Error al eliminar el servicio: ' . $e->getMessage()];
                }
            }
        }
        echo json_encode($respuesta);
    break;
}
?>