<?php

require_once 'http.php';
require_once 'login.php';

$seriesUrl = 'https://anime-on-demand.de' . $_GET['url'];
$responseHeaders = [];

$seriesSource = sendRequest($seriesUrl, 'GET', [
    'Cookie: ' . $sessionToken
], null, $responseHeaders);
$sessionToken = $responseHeaders['Set-Cookie'];

preg_match('/csrf-token" content="([^"]*)"/', $seriesSource, $matches);
$csrfToken = $matches[1];

preg_match('/data-playlist="([^"]*)"/', $seriesSource, $matches);
$dataPlaylistUrl = $matches[1];

$dataPlaylistSource = sendRequest('https://anime-on-demand.de' . $dataPlaylistUrl, 'GET', [
    'Accept: application/json',
    'Cookie: ' . $sessionToken,
    'X-CSRF-Token: ' . $csrfToken,
    'Referer: ' . $seriesUrl,
    'X-Requested-With: XMLHttpRequest'
], null, $responseHeaders);

$dataPlaylist = json_decode($dataPlaylistSource, true);
$sourceUrl = $dataPlaylist['playlist'][0]['sources'][0]['file'];

header("Location: " . $sourceUrl, true, 302);

echo $sourceUrl;