<?php

function loadEnv($path)
{
    if (!file_exists($path)) {
        throw new Exception(".env file not found at path: $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        if (!array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
        }

        if (!array_key_exists($key, $_SERVER)) {
            $_SERVER[$key] = $value;
        }

        putenv("$key=$value");
    }
}

loadEnv(__DIR__ . '/../.env');

function uploadJsonToDatabase($jsonData) {

    $host = $_ENV['DB_HOST'];
    $dbname = $_ENV['DB_NAME'];
    $username = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASSWORD'];
    
    $mysqli = new mysqli($host, $username, $password, $dbname);

    if ($mysqli->connect_error) {
        http_response_code(500);
        echo json_encode(['error' => 'Connection failed: ' . $mysqli->connect_error]);
        exit();
    }

    $mysqli->begin_transaction();

    try {
        $clearSql = 'DELETE FROM ordered_games_list';
        if (!$mysqli->query($clearSql)) {
            throw new Exception('Error clearing table: ' . $mysqli->error);
        }

        $insertSql = 'INSERT INTO ordered_games_list (games, updated_at) VALUES (?, ?)';
        $stmt = $mysqli->prepare($insertSql);

        if (!$stmt) {
            throw new Exception('Error preparing statement: ' . $mysqli->error);
        }

        $updatedAt = date('Y-m-d');
        $stmt->bind_param('ss', $jsonData, $updatedAt);

        if (!$stmt->execute()) {
            throw new Exception('Error executing statement: ' . $stmt->error);
        }

        $mysqli->commit();

        $stmt->close();
        $mysqli->close();

        http_response_code(200);
        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        $mysqli->rollback();

        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonData = file_get_contents('php://input');

    json_decode($jsonData);

    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON data']);
        exit();
    }
    
    uploadJsonToDatabase($jsonData);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>
