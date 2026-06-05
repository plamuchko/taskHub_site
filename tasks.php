<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit(0);

$host = 'db'; $db = 'taskhub_db'; $user = 'root'; $pass = 'task_secret_pass';
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query('SELECT * FROM tasks ORDER BY created_at DESC');
    echo json_encode($stmt->fetchAll());
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $title = trim($input['title'] ?? '');
    
    if ($title) {
        $stmt = $pdo->prepare('INSERT INTO tasks (title, status) VALUES (?, "todo")');
        $stmt->execute([$title]);
        echo json_encode(['success' => true]);
    }
}