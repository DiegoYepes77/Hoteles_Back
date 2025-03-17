<?php
class CorsHandler {
    private $allowed_origins = [
        'http://localhost:3000',
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        'http://127.0.0.1:3000',
        'http://localhost'
    ];

    public function handleCors() {
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

        if (in_array($origin, $this->allowed_origins)) {
            header("Access-Control-Allow-Origin: " . $origin);
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
            header("Access-Control-Allow-Credentials: true");
            
            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
                http_response_code(200);
                exit();
            }
        }
    }

    public function addOrigin($origin) {
        if (!in_array($origin, $this->allowed_origins)) {
            $this->allowed_origins[] = $origin;
        }
    }

    public function removeOrigin($origin) {
        $key = array_search($origin, $this->allowed_origins);
        if ($key !== false) {
            unset($this->allowed_origins[$key]);
        }
    }
}

// Create instance for global use
$corsHandler = new CorsHandler();

// Function for backward compatibility
function cors() {
    global $corsHandler;
    $corsHandler->handleCors();
}
?>