<?php

class CompanyClass
{
    public function normalizeCompanyData(array $data): ?array
    {
        if (!$this->isCompanyDataValid($data)) {
            return null;
        }

        $normalizedData = [
            'name' => strtolower(trim($data['name'])),
        ];

        if (!empty($data['website'])) {
            $normalizedData['website'] = $this->sanitizeWebsite($data['website']);
        }

        $address = $data['address'] ?? null;
        $normalizedData['address'] = $this->sanitizeAddress($address);

        return $normalizedData;
    }

    private function isCompanyDataValid(array $data): bool
    {
        return !empty($data['name']);
    }

    private function sanitizeWebsite(?string $website): string
    {
        $website = trim($website);
        return preg_match('/^https?:\/\//i', $website)
            ? parse_url($website, PHP_URL_HOST) ?? ''
            : $website;
    }

    private function sanitizeAddress(?string $address): ?string
    {
        $address = trim($address ?? '');
        return $address !== '' ? $address : null;
    }
}

$testInputs = [
    [
        'name' => ' OpenAI ',
        'website' => 'https://openai.com ',
        'address' => ' ',
    ],
    [
        'name' => 'Innovatiespotter',
        'address' => 'Groningen',
    ],
    [
        'name' => ' Apple ',
        'website' => '<HIDDEN INPUT> ',
    ],
];

$company = new CompanyClass();

foreach ($testInputs as $input) {
    $result = $company->normalizeCompanyData($input);
    var_dump($result);
}
