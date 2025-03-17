<?php
class Room {
    private $conn;
    private $table_name = "rooms";

    public $id_room;
    public $id_hotel;
    public $room_number;
    public $tipo_habitacion;
    public $tipo_acomodacion;
    public $precio_noche;
    public $estado;
    public $capacidad;
    public $descripcion;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                (id_hotel, room_number, tipo_habitacion, tipo_acomodacion, 
                precio_noche, estado, capacidad, descripcion)
                VALUES
                (:id_hotel, :room_number, :tipo_habitacion, :tipo_acomodacion,
                :precio_noche, :estado, :capacidad, :descripcion)";

        $stmt = $this->conn->prepare($query);

        // Ingreso de datos
        $this->id_hotel = htmlspecialchars(strip_tags($this->id_hotel));
        $this->room_number = htmlspecialchars(strip_tags($this->room_number));
        $this->tipo_habitacion = htmlspecialchars(strip_tags($this->tipo_habitacion));
        $this->tipo_acomodacion = htmlspecialchars(strip_tags($this->tipo_acomodacion));
        $this->precio_noche = htmlspecialchars(strip_tags($this->precio_noche));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->capacidad = htmlspecialchars(strip_tags($this->capacidad));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));

        // Envio de datos
        $stmt->bindParam(":id_hotel", $this->id_hotel);
        $stmt->bindParam(":room_number", $this->room_number);
        $stmt->bindParam(":tipo_habitacion", $this->tipo_habitacion);
        $stmt->bindParam(":tipo_acomodacion", $this->tipo_acomodacion);
        $stmt->bindParam(":precio_noche", $this->precio_noche);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":capacidad", $this->capacidad);
        $stmt->bindParam(":descripcion", $this->descripcion);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

public function validateHotelCapacity() {
    $query = "SELECT 
                h.cantidad as hotel_capacity,
                COUNT(r.id_room) as total_rooms
            FROM 
                hotels h
            LEFT JOIN 
                rooms r ON h.id_hotel = r.id_hotel
            WHERE 
                h.id_hotel = :id_hotel
            GROUP BY 
                h.id_hotel, h.cantidad";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id_hotel", $this->id_hotel);
    $stmt->execute();

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $hotel_capacity = (int)$row['hotel_capacity'];
        $current_rooms = (int)$row['total_rooms'];
        
        return (object)[
            'ok' => $current_rooms < $hotel_capacity,
            'msg' => $current_rooms < $hotel_capacity ? 
                "Room capacity available" : 
                "Hotel has reached maximum capacity of {$hotel_capacity} rooms"
        ];
    }
    return (object)[
        'ok' => false,
        'msg' => "Hotel not found or invalid capacity"
    ];
}

public function validateUniqueRoomType() {
    $query = "SELECT COUNT(*) as count
            FROM rooms 
            WHERE id_hotel = :id_hotel 
            AND tipo_habitacion = :tipo_habitacion 
            AND tipo_acomodacion = :tipo_acomodacion";

    $stmt = $this->conn->prepare($query);
    
    $stmt->bindParam(":id_hotel", $this->id_hotel);
    $stmt->bindParam(":tipo_habitacion", $this->tipo_habitacion);
    $stmt->bindParam(":tipo_acomodacion", $this->tipo_acomodacion);
    
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return (object)[
        'ok' => $row['count'] == 0,
        'msg' => $row['count'] == 0 ? 
            "Room type combination is unique" : 
            "This room type and accommodation combination already exists"
    ];
}

}
?>