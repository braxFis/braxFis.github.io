<?php
namespace modules\game\models;

class Gallery
{
    private $apiUrl = "https://api.rawg.io/api/games";
    private $apiKey = "8bc47dab600645ac9164f534d0182baf";

    public function getGallery($date = '2025-01-01', $order = 'created')
    {
        $params = [
            'key' => $this->apiKey,
            'dates' => $date,
            'ordering' => $order,
        ];

        $url = $this->apiUrl . '?' . http_build_query($params);
        $response = file_get_contents($url);

        if (!$response) {
            throw new \Exception("API request failed");
        }

        $data = json_decode($response, true);

        if (!isset($data['results'])) {
            return [];
        }

        // returnerar bara relevanta fält
        return array_map(fn($item) => [
            'name' => $item['name'] ?? 'Unknown',
            'background_image' => $item['background_image'] ?? null
        ], $data['results']);
    }
}
