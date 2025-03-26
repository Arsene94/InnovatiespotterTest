<?php

class ExportService
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function exportToCSV($filePath)
    {
        $sql = "SELECT * FROM normalized_companies ORDER BY name";

        $stmt = $this->pdo->query($sql);
        $results = $stmt->fetchAll();

        $file = fopen($filePath, 'w');
        fputcsv($file, ['id', 'name', 'canonical_website', 'address']); // Header

        foreach ($results as $row) {
            fputcsv($file, $row);
        }

        fclose($file);

        echo "Exported to CSV: $filePath\n";
    }
}