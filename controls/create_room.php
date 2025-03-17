<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Content-Type: application/json');

include_once '../connection/database.php';
include_once '../class/Room.php';
include_once '../connection/connections.php';

$corsHandler->handleCors();

$database = new Database();
$db = $database->getConnection();

$room = new Room($db);

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->id_hotel) &&
    !empty($data->room_number) &&
    !empty($data->tipo_habitacion) &&
    !empty($data->tipo_acomodacion) &&
    !empty($data->precio_noche) &&
    !empty($data->capacidad)
){
    $room->id_hotel = $data->id_hotel;
    $room->room_number = $data->room_number;
    $room->tipo_habitacion = $data->tipo_habitacion;
    $room->tipo_acomodacion = $data->tipo_acomodacion;
    $room->precio_noche = $data->precio_noche;
    $room->estado = isset($data->estado) ? $data->estado : "disponible";
    $room->capacidad = $data->capacidad;
    $room->descripcion = isset($data->descripcion) ? $data->descripcion : "";

    $capacityCheck = $room->validateHotelCapacity();
    if(!$capacityCheck->ok) {
        http_response_code(400);
        echo json_encode(array("message" => $capacityCheck->msg));
        exit();
    }

    $uniqueCheck = $room->validateUniqueRoomType();
    if(!$uniqueCheck->ok) {
        http_response_code(400);
        echo json_encode(array("message" => $uniqueCheck->msg));
        exit();
    }

    if($room->create()){
        http_response_code(201);
        echo json_encode(array("message" => "Room was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create room."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create room. Data is incomplete."));
}

?>