<?php

require_once '../vendor/autoload.php';

use Thiago\ParcelApi\Controllers\CarnesController;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $controller = new CarnesController();
    $controller->criarCarne();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
}
