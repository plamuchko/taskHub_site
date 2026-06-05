<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit(0);

$host = 'db'; $db = 'taskhub_db'; $user = 'root'; $pass = 'task_secret_pass';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'DB Connection Error']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

if ($action === 'register') {
    $user = trim($input['username'] ?? '');
    $pass = trim($input['password'] ?? '');
    
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
    $stmt->execute([$user]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Потребителят съществува!']);
        exit;
    }
    
    $hashed = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
    if ($stmt->execute([$user, $hashed])) {
        echo json_encode(['success' => true, 'username' => $user]);
    }
} elseif ($action === 'login') {
    $user = trim($input['username'] ?? '');
    $pass = trim($input['password'] ?? '');
    
    $stmt = $pdo->prepare('SELECT password FROM users WHERE username = ?');
    $stmt->execute([$user]);
    $row = $stmt->fetch();
    
    if ($row && password_verify($pass, $row['password'])) {
        echo json_encode(['success' => true, 'username' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Грешни данни!']);
    }
}