<?php
require_once 'Connector.php';
require_once 'Account.php';

$connector = new Connector();
$account = new Account($connector->getConnection());

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $account->create($data['name'], $data['email'], $data['password']);
            echo json_encode(['success' => $result]);
        }
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $account->update($data['id'], $data['name'], $data['email']);
            echo json_encode(['success' => $result]);
        }
        break;

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $account->delete($data['id']);
            echo json_encode(['success' => $result]);
        }
        break;

    case 'getAll':
        $users = $account->getAll();
        echo json_encode($users);
        break;

    case 'getById':
        if (isset($_GET['id'])) {
            $user = $account->getById($_GET['id']);
            echo json_encode($user);
        }
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
}
?>
