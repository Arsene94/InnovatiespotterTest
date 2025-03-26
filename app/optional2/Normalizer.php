<?php

class Normalizer
{
    public static function normalizeCompany(array $companies): ?array
    {
        usort($companies, function ($a, $b) {
            $priority = ['MANUAL' => 1, 'API' => 2, 'SCRAPER' => 3];
            return ($priority[$a['source']] ?? 4) <=> ($priority[$b['source']] ?? 4);
        });

        $mostReliable = $companies[0];

        return [
            'name' => strtolower(trim($mostReliable['name'])),
            'website' => filter_var($mostReliable['website'], FILTER_SANITIZE_URL),
            'address' => trim($mostReliable['address']) ?: null,
        ];
    }
}