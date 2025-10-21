<?php
// ===== KONFIGURATION =====
$apiKey = "Bearer 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";  // ← byt ut till din riktiga NVIDIA API-nyckel
$functionId = "5e607c81-7aa6-44ce-a11d-9e08f0a3fe49"; // Samma som i din Python-version
$voice = "English-US-RadTTS.Male-Neutral";
$text = "";
$outputFile = "";

// ===== FÖRBERED DATA =====
$url = "https://grpc.nvcf.nvidia.com/v1/functions/$functionId/invoke";

$payload = json_encode([
    "text" => $text,
    "voice" => $voice,
    "encoding" => "wav"
]);

$headers = [
    "Authorization: $apiKey",
    "Content-Type: application/json"
];

// ===== GÖR FÖRFRÅGAN =====
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    die("cURL-fel: " . curl_error($ch));
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// ===== HANTERA RESULTAT =====
if ($httpCode === 200) {
    // NVIDIA returnerar normalt base64-ljud
    $data = json_decode($response, true);
    if (isset($data["audio"]) && $data["audio"] !== "") {
        $audioData = base64_decode($data["audio"]);
        file_put_contents($outputFile, $audioData);
        echo "✅ Ljudfil skapad: $outputFile";
    } else {
        echo "⚠️ Inget ljud returnerades. Kontrollera röst och text.";
    }
} else {
    echo "❌ Serverfel ($httpCode):<br>";
    echo "<pre>$response</pre>";
}
