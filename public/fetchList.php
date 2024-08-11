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

$sql = 'SELECT id, name FROM ordered_games_list';
$result = $mysqli->query($sql);

if ($result === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Query error: ' . $mysqli->error]);
    exit();
}

$results = [];
while ($row = $result->fetch_assoc()) {
    if (isset($row['games'])) {
        $row['games'] = json_decode($row['games'], true);
    }
    $results[] = $row;
}

$result->free();
$mysqli->close();

header('Content-Type: application/json');

echo json_encode($results);
?>
