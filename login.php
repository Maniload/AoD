<?php

require_once 'http.php';

const LOGIN_URL = 'https://anime-on-demand.de/users/sign_in';

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="Test Realm"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
}

$sessionToken = login($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);

function login(string $username, string $password) : string
{
    $responseHeaders = [];
    $matches = [];

    $loginSource = sendRequest(LOGIN_URL, 'GET', [
        'Content-Type: text/plain'
    ], null, $responseHeaders);

    if (!preg_match('/name="authenticity_token" value="([^"]*)"/', $loginSource, $matches)) {
        die('Es wurde kein Authenticity Token gefunden!');
    }

    $query = http_build_query([
        'user[login]' => $username,
        'user[password]' => $password,
        'user[remember_me]' => '1',
        'authenticity_token' => $matches[1],
        'utf8' => '✓',
        'commit' => 'Einloggen'
    ], '', '&', PHP_QUERY_RFC3986);
    $headers = [
        'Content-Type: application/x-www-form-urlencoded',
        'Cookie: ' . $responseHeaders['Set-Cookie']
    ];
    sendRequest(LOGIN_URL, 'POST', $headers, $query, $responseHeaders);

    return $responseHeaders['Set-Cookie'];
}