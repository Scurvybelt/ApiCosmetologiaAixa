<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('content-type: application/json; charset=utf-8');
require 'servicesModel.php';
$servicesModel= new servicesModel();
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
        $_POST= json_decode(file_get_contents('php://input',true));
        if(!isset($_POST->name) || is_null($_POST->name) || empty(trim($_POST->name)) || strlen($_POST->name) > 80){
            $respuesta= ['error','El nombre del producto no debe estar vacío y no debe de tener más de 80 caracteres'];
        }
        else if(!isset($_POST->description) || is_null($_POST->description) || empty(trim($_POST->description)) || strlen($_POST->name) > 150){
            $respuesta= ['error','La descripción del producto no debe estar vacía y no debe de tener más de 150 caracteres'];
        }
        else if(!isset($_POST->price) || is_null($_POST->price) || empty(trim($_POST->price)) || !is_numeric($_POST->price) || strlen($_POST->price) > 20){
            $respuesta= ['error','El precio del producto no debe estar vacío, debe ser de tipo numérico y no tener más de 20 caracteres'];
        }
        else{
            $respuesta = $servicesModel->saveServices($_POST->name,$_POST->description,$_POST->price,$_POST->img);
        }
        echo json_encode($respuesta);
    break;

    case 'PUT':
        $_PUT= json_decode(file_get_contents('php://input',true));
        if(!isset($_PUT->id) || is_null($_PUT->id) || empty(trim($_PUT->id))){
            $respuesta= ['error','El ID del producto no debe estar vacío'];
        }
        else if(!isset($_PUT->name) || is_null($_PUT->name) || empty(trim($_PUT->name)) || strlen($_PUT->name) > 80){
            $respuesta= ['error','El nombre del producto no debe estar vacío y no debe de tener más de 80 caracteres'];
        }
        else if(!isset($_PUT->description) || is_null($_PUT->description) || empty(trim($_PUT->description)) || strlen($_PUT->description) > 150){
            $respuesta= ['error','La descripción del producto no debe estar vacía y no debe de tener más de 150 caracteres'];
        }
        else if(!isset($_PUT->price) || is_null($_PUT->price) || empty(trim($_PUT->price)) || !is_numeric($_PUT->price) || strlen($_PUT->price) > 20){
            $respuesta= ['error','El precio del producto no debe estar vacío , debe ser de tipo numérico y no tener más de 20 caracteres'];
        }
        else{
            $respuesta = $servicesModel->updateServices($_PUT->id,$_PUT->name,$_PUT->description,$_PUT->price);
        }
        echo json_encode($respuesta);
    break;

    case 'DELETE';
        $_DELETE= json_decode(file_get_contents('php://input',true));
        if(!isset($_DELETE->id) || is_null($_DELETE->id) || empty(trim($_DELETE->id))){
            $respuesta= ['error','El ID del producto no debe estar vacío'];
        }
        else{
            $respuesta = $servicesModel->deleteServices($_DELETE->id);
        }
        echo json_encode($respuesta);
    break;
}