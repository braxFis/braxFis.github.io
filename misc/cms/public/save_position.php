<?php
// Läs inkommande JSON
$input = file_get_contents('php://input');

// Debug: skriv till fil för att se vad som tas emot
file_put_contents('debug.txt', $input);

// Konvertera JSON till PHP-array
$data = json_decode($input, true);

// Kontrollera att vi fick rätt data
if ($data && isset($data['elementId'], $data['dropzoneId'])) {
    // Spara i position.json
    file_put_contents('position.json', json_encode($data));
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}
?>
