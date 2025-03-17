<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Content-Type: application/json');

include_once '../connection/database.php';
include_once '../class/Hotel.php';
include_once '../connection/connections.php';

$corsHandler->handleCors();

$database = new Database();
$db = $database->getConnection();

$hotel = new Hotel($db);



$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->id_hotel) &&
    !empty($data->nombre) &&
    !empty($data->direccion) &&
    !empty($data->telefono) &&
    !empty($data->email) &&
    !empty($data->cantidad) &&
    !empty($data->ruc) &&
    !empty($data->razon_social) &&
    !empty($data->direccion_fiscal) &&
    !empty($data->regimen_tributario)
){
    $hotel->id_hotel = $data->id_hotel;
    $hotel->nombre = $data->nombre;
    $hotel->direccion = $data->direccion;
    $hotel->telefono = $data->telefono;
    $hotel->email = $data->email;
    $hotel->cantidad = $data->cantidad;
    $hotel->ruc = $data->ruc;
    $hotel->razon_social = $data->razon_social;
    $hotel->direccion_fiscal = $data->direccion_fiscal;
    $hotel->regimen_tributario = $data->regimen_tributario;

    echo $hotel->nombre;

    if($hotel->create()){
        http_response_code(201);
        echo json_encode(array("message" => "Hotel was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create hotel."));
    }
}
else {
    http_response_code(400);
    echo json_encode(array("message" => $db));
}
?>