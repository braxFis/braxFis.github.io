<?php

class OpenAIClient {
    private $apiKey;
    private $endpoint = "https://api.openai.com/v1/chat/completions";

    public function __construct(string $apiKey) {
        $this->apiKey = $apiKey;
    }

    public function generateText(string $prompt, string $model = "gpt-4.1") {
        $data = [
            "model" => $model,
            "messages" => [
                ["role" => "user", "content" => $prompt]
            ],
            "temperature" => 0.7
        ];

        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer {$this->apiKey}"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception("cURL error: " . curl_error($ch));
        }

        curl_close($ch);
        return json_decode($response, false); // returnerar som objekt ->
    }
}

// --------------------------
// Användning
// --------------------------

$apiKey = "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";
$client = new OpenAIClient($apiKey);

try {
    $prompt = "";
    $result = $client->generateText($prompt, "gpt-4.1");

    // Eftersom vi har json_decode($response, false), kan vi nu använda ->
    echo "Svar från GPT-4.1:\n";
    echo $result->choices[0]->message->content;
} catch (Exception $e) {
    echo "Fel: " . $e->getMessage();
}
