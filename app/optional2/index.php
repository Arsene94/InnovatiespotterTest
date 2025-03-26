<?php

require_once 'DatabaseConnection.php';
require_once 'CompanyService.php';
require_once 'Normalizer.php';
require_once 'NormalizedCompanyService.php';
require_once 'ExportService.php';

// Initialize Database Connection
$dbConnection = new DatabaseConnection();
$pdo = $dbConnection->getConnection();

// Identify Duplicates
$companyService = new CompanyService($pdo);
$duplicates = $companyService->fetchPotentialDuplicates();
echo "Potential Duplicates:\n";
print_r($duplicates);

// Normalize and Insert into `normalized_companies`
$normalizedService = new NormalizedCompanyService($pdo);
$normalizedService->insertNormalizedCompanies();
echo "Normalized companies inserted successfully.\n";

// Export to CSV
$exportService = new ExportService($pdo);
$exportService->exportToCSV('normalized_companies.csv');
echo "Export completed.\n";