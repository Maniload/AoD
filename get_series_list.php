<?php

require_once 'http.php';

$listSource = sendRequest('https://anime-on-demand.de/animes');
$matches = [];
$json = [];

preg_match_all('/<h3 class="animebox-title">([^<]*)<\/h3>.*?<img src="([^"]*)".*?<a href="([^"]*)">.*?<p class="animebox-shorttext">([^<]*)<\/p>/s', $listSource, $matches, PREG_SET_ORDER);

foreach ($matches as $match) {
    $json[] = [
        'title' => $match[1],
        'image' => $match[2],
        'description' => $match[4],
        'url' => $match[3],
        'id' => substr($match[3], strrpos($match[3], '/') + 1)
    ];
}

header('Content-Type: application/json');

echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);