<?php

class CompanyService
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function fetchPotentialDuplicates()
    {
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

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}