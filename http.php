<?php


function sendRequest($url, $method = 'GET', array $headers = [
    'Content-Type: text/plain'
], $body = null, array &$responseHeaders = [], bool $allowRedirects = false) {
    $options = [
        'http' => [
            'header' => $headers,
            'method' => $method,
            'follow_location' => $allowRedirects ? 1 : 0
        ]
    ];
    if (!is_null($body)) {
        $options['http']['content'] = $body;
    }
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $responseHeaders = parseHeaders($http_response_header);
    return $response;
}

function parseHeaders($headers) {
    $headersAssoc = [];
    foreach ($headers as $header) {
        $header = explode(': ', $header);
        if (count($header) == 2) {
            $headersAssoc[$header[0]] = $header[1];
        } else {
            $headersAssoc[] = $header[0];
        }
    }
    return $headersAssoc;
}