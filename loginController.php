<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('content-type: application/json; charset=utf-8');
require 'loginModel.php';

$loginModel = new loginModel();

$requestUri = $_SERVER['REQUEST_URI'];
$endpoint = explode('/', trim($requestUri, '/'))[0]; 

switch($_SERVER['REQUEST_METHOD']){
    case 'POST':
        $_POST = json_decode(file_get_contents('php://input'), true);

        // Inicio de Sesión
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $respuesta = $loginModel->validateUser($_POST['username'], $_POST['password']);
            echo json_encode($respuesta);
        } 
        // Registro de nuevo usuario
        else if (isset($_POST['user']) && isset($_POST['password']) && isset($_POST['email'])) {
            $respuesta = $loginModel->saveUsers($_POST['user'], $_POST['password'], $_POST['email']);
            echo json_encode($respuesta);
        } 
        else {
            echo json_encode(['error', 'Solicitud inválida']);
        }
    break;

    case 'PUT':
        $_PUT = json_decode(file_get_contents('php://input'), true);

        // Actualización de Contraseña
        if (isset($_PUT['id']) && isset($_PUT['current_password']) && isset($_PUT['new_password'])) {
            $respuesta = $loginModel->updateUserPassword($_PUT['id'], $_PUT['current_password'], $_PUT['new_password']);
            echo json_encode($respuesta);
        } 
        // Actualización de Id, Usuario o Correo
        else if (isset($_PUT['id']) && isset($_PUT['username']) && isset($_PUT['email'])) {
            // Check for optional admin and active status
            $is_admin = isset($_PUT['is_admin']) ? $_PUT['is_admin'] : null;
            $active = isset($_PUT['active']) ? $_PUT['active'] : null;
            
            $respuesta = $loginModel->updateUser($_PUT['id'], $_PUT['username'], $_PUT['email'], $is_admin, $active);
            echo json_encode($respuesta);
        } 
        else {
            echo json_encode(['error', 'Solicitud de actualización inválida']);
        }
    break;

    case 'DELETE':
        $_DELETE = json_decode(file_get_contents('php://input'), true);

        // Eliminar usuario
        if (isset($_DELETE['id'])) {
            $respuesta = $loginModel->deleteUser($_DELETE['id']);
            echo json_encode($respuesta);
        } 
        else {
            echo json_encode(['error', 'ID de usuario no proporcionado']);
        }
    break;

    case 'GET':
        switch($endpoint) {
            case 'users':
                try {
                    $users = $loginModel->getUsers();
                    
                    $users = array_map(function($user) {
                        unset($user['password']);
                        return $user;
                    }, $users);

                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Usuarios recuperados exitosamente',
                        'data' => $users,
                        'total' => count($users)
                    ]);
                } catch (Exception $e) {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Error al recuperar usuarios: ' . $e->getMessage()
                    ]);
                }
                break;

            case 'login':
                if (isset($_GET['username']) && isset($_GET['password'])) {
                    $respuesta = $loginModel->validateUser($_GET['username'], $_GET['password']);
                    echo json_encode($respuesta);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Credenciales de inicio de sesión incompletas'
                    ]);
                }
                break;

            default:
                http_response_code(404);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Endpoint no encontrado'
                ]);
                break;
        }
    break;

    default:
        echo json_encode(['error', 'Método HTTP no soportado']);
    break;
}
?>