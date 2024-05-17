<?php
require_once 'beast.php';

$jsonFile = 'beasts.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

$fantasticBeasts = [];

foreach ($data as $beastData) {
    $beast = new FantasticBeast(
        $beastData['name'],
        $beastData['classification'],
        $beastData['description']
    );
    $fantasticBeasts[] = $beast;
}
?>