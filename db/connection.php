<?php

$databaseName = "Abari";
$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$databaseName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = file_get_contents(basePath('db/tables.sql'));
    $conn->exec($sql);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die(); // Stop further execution
}

function query(PDO $conn, string $sql, array $params = [], int $limit = 10, int $page = 1)
{
    try {
        $offset = ($page - 1) * $limit;
        $sqlWithLimit = $sql . " LIMIT :limit OFFSET :offset";
        $stmt = $conn->prepare($sqlWithLimit);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalStmt = $conn->prepare("SELECT COUNT(*) FROM (" . $sql . ") as count_query");
        foreach ($params as $key => $value) {
            $totalStmt->bindValue($key, $value);
        }
        $totalStmt->execute();
        $total_results = $totalStmt->fetch(PDO::FETCH_ASSOC)['COUNT(*)'];
        $total_pages = ceil($total_results / $limit);

        return [
            'data' => $data,
            'total' => $total_results,
            'total_pages' => $total_pages,
            'current_page' => $page,
            'limit' => $limit,
            'error' => false
        ];
    } catch (PDOException $e) {
        return ['error' => true, 'message' => $e->getMessage()];
    }
}

function query_create(PDO $conn, string $sql, array $params = [])
{
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception($e->getMessage());
    }
}
?>
