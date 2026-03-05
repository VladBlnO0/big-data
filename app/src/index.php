<!DOCTYPE html>
<html lang="en">

<head>
    <title>Movie Data</title>
</head>

<body>
    <h1>Movie Data</h1>
</body>

</html>
<?php

$json_string = file_get_contents('data.json');
$data = json_decode($json_string, true);

/** @var string $host host */
$host = getenv('POSTGRES_HOST') ?: 'postgres-db';
/** @var string $port port */
$port = getenv('POSTGRES_PORT') ?: '5432';
/** @var string $dbname dbname */
$dbname = getenv('POSTGRES_DB');
/** @var string $user user */
$user = getenv('POSTGRES_USER');
/** @var string $password password */
$password = getenv('POSTGRES_PASSWORD');

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    /** @var string $port PDO instance*/
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to the PostgreSQL";

    $sql_create = "CREATE TABLE IF NOT EXISTS movies (
        imdb_id VARCHAR(50) PRIMARY KEY,
        title TEXT,
        release_year INTEGER,
        rating DECIMAL(3,1),
        description TEXT    
    )";
    $stmt_create = $pdo->prepare($sql_create);
    $stmt_create->execute();
    echo "Table 'movies' created successfully!<br>";

    foreach ($data as $movie) {
        $stmt = $pdo->prepare('
        INSERT INTO movies (imdb_id, title, release_year, rating, description)
        VALUES (:id, :title, :year, :rating, :desc)
        ON CONFLICT (imdb_id) DO NOTHING
    ');
        $stmt->execute([
            'id' => $movie['id'],
            'title' => $movie['primaryTitle'],
            'year' => $movie['startYear'],
            'rating' => $movie['averageRating'],
            'desc' => $movie['description']
        ]);
    }


    echo "<pre>";
    $stmt = $pdo->prepare("SELECT * FROM movies LIMIT 5");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($result);
    echo "</pre>";

    echo "<pre>";
    $stmt = $pdo->prepare("SELECT * FROM movies WHERE movies.rating >= 9");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($result);
    echo "</pre>";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>