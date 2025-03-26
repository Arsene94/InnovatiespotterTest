<?php

class Normalizer
{
    public static function normalizeCompany(array $companies): ?array
    {
        if (empty($companies)) {
            return null;
        }

        usort($companies, function ($a, $b) {
            $priority = ['MANUAL' => 1, 'API' => 2, 'SCRAPER' => 3];
            return ($priority[$a['source']] ?? 4) <=> ($priority[$b['source']] ?? 4);
        });

        $mostReliable = $companies[0];

        if (empty($mostReliable['name']) || empty($mostReliable['website'])) {
            return null;
        }

        return [
            'name' => strtolower(trim($mostReliable['name'])),
            'website' => filter_var($mostReliable['website'], FILTER_SANITIZE_URL) ?: null,
            'address' => trim($mostReliable['address'] ?? '') ?: null,
        ];
    }
}