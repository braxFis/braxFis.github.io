<?php


$apiKey = "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";

// 1️⃣ Prepare request data
$data = [
    "model" => "dall-e-3",
    "prompt" => "",
    "n" => 1,
    "size" => "1792x1024",
    "quality" => "hd"
];

$ch = curl_init("https://api.openai.com/v1/images/generations");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// 2️⃣ Execute request
$response = curl_exec($ch);
if(curl_errno($ch)) {
    die("cURL error: " . curl_error($ch));
}
curl_close($ch);

// 3️⃣ Decode JSON
$responseData = json_decode($response, true);
if (!isset($responseData['data'][0]['url'])) {
    die("Ingen bild returnerades.");
}

$imageUrl = $responseData['data'][0]['url'];
echo "Image URL: $imageUrl\n";

// 4️⃣ Download image
$imageContent = file_get_contents($imageUrl);
if ($imageContent === false) {
    die("Kunde inte hämta bilden.");
}

// 5️⃣ Save to file
$fileName = "";
file_put_contents($fileName, $imageContent);

echo "Bild sparad som $fileName\n";
