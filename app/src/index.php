<?php
// declare(strict_types=1);

$host = getenv('POSTGRES_HOST') ?: 'postgres-db';
$port = getenv('POSTGRES_PORT') ?: '5432';
$dbname = getenv('POSTGRES_DB');
$user = getenv('POSTGRES_USER');
$password = getenv('POSTGRES_PASSWORD');

// Construct the DSN (Data Source Name)
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {

    // Create a new PDO instance
    $pdo = new PDO($dsn, $user, $password);

    // Set PDO attributes (optional, but recommended for error handling)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to the PostgreSQL database successfully with PDO!";

    // Example query using prepared statements to prevent SQL injection
    $stmt = $pdo->prepare("SELECT * FROM coolscema.cooldata");

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<pre>";
    print_r($result);
    echo "</pre>";

    // Process the results...

} catch (PDOException $e) {
    // Handle connection errors
    echo "Connection failed: " . $e->getMessage();
}
