<?php

namespace app\models;

class CustomField
{
    private string $dataFile;

    public function __construct()
    {
        $this->dataFile = __DIR__ . '/../../storage/customfields.json';
        if (!file_exists($this->dataFile)) {
            file_put_contents($this->dataFile, json_encode([]));
        }
    }

    public function getAll(): array
    {
        return json_decode(file_get_contents($this->dataFile), true) ?? [];
    }

    public function saveAll(array $fields): void
    {
        file_put_contents($this->dataFile, json_encode($fields, JSON_PRETTY_PRINT));
    }
}
