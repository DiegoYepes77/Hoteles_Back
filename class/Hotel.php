<?php



class Hotel {
    private $conn;
    private $table_name = "hotels";

    public $id_hotel;
    public $nombre;
    public $direccion;
    public $telefono;
    public $email;
    public $cantidad;
    public $ruc;
    public $razon_social;
    public $direccion_fiscal;
    public $regimen_tributario;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                (id_hotel,nombre, direccion, telefono, email,
                cantidad, ruc, 
                razon_social, direccion_fiscal, regimen_tributario)
                VALUES
                (:id_hotel, :nombre, :direccion, :telefono, :email,
                :cantidad, :ruc,
                :razon_social, :direccion_fiscal, :regimen_tributario)";

        $stmt = $this->conn->prepare($query);

        // Ingreso de datos
        $this->id_hotel = htmlspecialchars(strip_tags($this->id_hotel));
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->ruc = htmlspecialchars(strip_tags($this->ruc));
        $this->razon_social = htmlspecialchars(strip_tags($this->razon_social));
        $this->direccion_fiscal = htmlspecialchars(strip_tags($this->direccion_fiscal));
        $this->regimen_tributario = htmlspecialchars(strip_tags($this->regimen_tributario));

        // Envio de datos
        $stmt->bindParam(":id_hotel", $this->id_hotel);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":ruc", $this->ruc);
        $stmt->bindParam(":razon_social", $this->razon_social);
        $stmt->bindParam(":direccion_fiscal", $this->direccion_fiscal);
        $stmt->bindParam(":regimen_tributario", $this->regimen_tributario);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}

?>