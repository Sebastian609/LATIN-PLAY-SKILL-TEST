<?php
declare(strict_types=1);

require_once __DIR__ . '/../controllers/NodeController.php';
require_once __DIR__ . '/../repository/NodeRepository.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

$controller = new NodeController(new NodeService(new NodeRepository()));

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $controller->index();
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        $controller->store($data);
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        $controller->delete($data);
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        $controller->changeFather($data);
        break;
    
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        break;
}
