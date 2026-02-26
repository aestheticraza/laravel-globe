<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/master/countries.json');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$json = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($http_code !== 200 || !$json) {
    echo "Failed to fetch data. HTTP Code: " . $http_code . "\n";
    echo "cURL Error: " . curl_error($ch) . "\n";
    exit(1);
}
curl_close($ch);

$data = json_decode($json, true);
$countries = [];

foreach ($data as $country) {
    $countries[] = [
        'id' => 0,
        'name' => $country['name'],
        'iso2' => $country['iso2'],
        'iso3' => $country['iso3'],
        'phone_code' => $country['phone_code'],
        'capital' => $country['capital'],
        'region' => $country['region'],
        'subregion' => $country['subregion'],
        'latitude' => $country['latitude'],
        'longitude' => $country['longitude'],
        'emoji' => $country['emoji'],
        'is_active' => true
    ];
}

usort($countries, function ($a, $b) {
    return strcmp($a['name'], $b['name']);
});

foreach ($countries as $k => $c) {
    $countries[$k]['id'] = $k + 1;
}

$file_path = __DIR__ . '/data/custom_countries.json';
file_put_contents($file_path, json_encode($countries, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Generated " . count($countries) . " countries successfully.\n";
