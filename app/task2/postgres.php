<?php

try {
    $conn = new PDO("pgsql:host=db;dbname=mydatabase", "myuser", "mypassword");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
        SELECT 
            LOWER(TRIM(name)) AS normalized_name,
            COUNT(*) AS occurrence_count,
            ARRAY_AGG(DISTINCT source) AS sources
        FROM companies
        GROUP BY LOWER(TRIM(name))
        HAVING COUNT(*) > 1
        ORDER BY occurrence_count DESC;
    ";

    $stmt = $conn->query($sql);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        echo "Name: " . $row['normalized_name'] . " - Occurrences: " . $row['occurrence_count'] . " - Sources: " . implode(", ", $row['sources']) . "<br>";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}