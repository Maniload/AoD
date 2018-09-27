<?php

require 'http.php';

$seriesSource = sendRequest('https://anime-on-demand.de' . $_GET['url']);
$matches = [];

preg_match('/"(https:\/\/.*?detail.*?)"/', $seriesSource, $matches);

$json = [
    'image' => $matches[1]
];

preg_match_all('/episodebox-title" title="([^"]*)".*?img src="([^"]*)".*?episodebox-shorttext">([^<]*)</s', $seriesSource, $matches, PREG_SET_ORDER);

$episodes = [];
foreach ($matches as $match) {
    $episodes[] = [
        'title' => $match[1],
        'image' => $match[2],
        'description' => $match[3]
    ];
}
$json['episodes'] = $episodes;

header('Content-Type: application/json');

echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);