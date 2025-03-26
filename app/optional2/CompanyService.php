<?php

class CompanyService
{
    private const FETCH_DUPLICATES_SQL = "
        SELECT
            LOWER(TRIM(name)) AS normalized_name,
            COUNT(*) AS occurrence_count,
            ARRAY_AGG(DISTINCT source) AS sources
        FROM companies
        GROUP BY LOWER(TRIM(name))
        HAVING COUNT(*) > 1
        ORDER BY occurrence_count DESC
    ";

    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function fetchPotentialDuplicates(): array
    {
        try {
            $stmt = $this->pdo->query(self::FETCH_DUPLICATES_SQL);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('Error fetching duplicates: ' . $e->getMessage());
            return [];
        }
    }
}