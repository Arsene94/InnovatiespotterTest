<?php

class NormalizedCompanyService
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function insertNormalizedCompanies()
    {
        $sql = "
            SELECT
                LOWER(TRIM(name)) AS normalized_name,
                array_agg(json_build_object(
                    'name', name,
                    'website', website,
                    'address', address,
                    'source', source
                )) AS grouped_companies
            FROM companies
            GROUP BY LOWER(TRIM(name));
        ";

        $stmt = $this->pdo->query($sql);
        $groupedResults = $stmt->fetchAll();

        foreach ($groupedResults as $result) {
            $groupedCompanies = json_decode($result['grouped_companies'], true);
            $normalizedData = Normalizer::normalizeCompany($groupedCompanies);

            if ($normalizedData) {
                $this->insertNormalizedCompany($normalizedData);
            }
        }
    }

    private function insertNormalizedCompany(array $normalizedData)
    {
        $sql = "
            INSERT INTO normalized_companies (name, canonical_website, address)
            VALUES (:name, :website, :address)
            ON CONFLICT (name) DO NOTHING;
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':name' => $normalizedData['name'],
            ':website' => $normalizedData['website'] ?? null,
            ':address' => $normalizedData['address'] ?? null,
        ]);
    }
}