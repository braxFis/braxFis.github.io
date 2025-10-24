<?php
class JourneyModel
{
    private $clientId = "AIHg7YkE1D3qDZAdT5Gj8PZ70SEa";
    private $clientSecret = "chPfiZKgepM7WH1lklGePGO71zYa";
    private $accessToken = "";

    public function __construct()
    {
        $this->accessToken = $this->getAccessToken();
    }

    private function getAccessToken()
    {
        $ch = curl_init("https://ext-api.vasttrafik.se/token");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Basic " . base64_encode($this->clientId . ":" . $this->clientSecret),
            "Content-Type: application/x-www-form-urlencoded"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        return $data['access_token'] ?? null;
    }

    private function request($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$this->accessToken}",
            "Accept: application/json"
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    public function searchJourney($origin, $destination)
    {
        $originData = $this->request("https://ext-api.vasttrafik.se/pr/v4/locations/by-text?q=" . urlencode($origin));
        $destinationData = $this->request("https://ext-api.vasttrafik.se/pr/v4/locations/by-text?q=" . urlencode($destination));

        $originGID = $originData['results'][0]['gid'] ?? '';
        $destinationGID = $destinationData['results'][0]['gid'] ?? '';

        if (!$originGID || !$destinationGID) {
            return ['error' => 'Missing GIDs'];
        }

        $journeyURL = "https://ext-api.vasttrafik.se/pr/v4/journeys?originGid=$originGID&destinationGid=$destinationGID";
        return $this->request($journeyURL);
    }

    public function searchProducts($origin, $destination)
    {
        $originData = $this->request("https://ext-api.vasttrafik.se/pr/v4/locations/by-text?q=" . urlencode($origin));
        $destinationData = $this->request("https://ext-api.vasttrafik.se/pr/v4/locations/by-text?q=" . urlencode($destination));

        $originGID = $originData['results'][0]['gid'] ?? '';
        $destinationGID = $destinationData['results'][0]['gid'] ?? '';

        $url = "https://ext-api.vasttrafik.se/pr/v4/products/journeyticket?originGid=$originGID&destinationGid=$destinationGID";
        return $this->request($url);
    }
}
