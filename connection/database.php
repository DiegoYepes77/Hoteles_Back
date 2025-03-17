<?php

class Database {
    private $host = "dpg-cvbhjdjtq21c73dvfj2g-a.oregon-postgres.render.com";
    private $database = "hotel_management_llf9";
    private $username = "hotel_management_llf9_user";
    private $password = "7ijLl2CvDBANxMYxcjWkvkM7axbmm5XO";
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "pgsql:host=" . $this->host . 
                ";dbname=" . $this->database,
                $this->username,
                $this->password
            );
            
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
            echo json_encode(array("message" =>  $e->getMessage()));

        }
        return $this->conn;
    }
}
?>