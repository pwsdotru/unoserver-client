<?php

declare(strict_types=1);

$url = "http://127.0.0.1:2003"; //Host nad port for Unoserver
$method = "info"; //check for convert method
$params = [];

$request = xmlrpc_encode_request($method, $params);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    printf("cURL error No %d %s\n", curl_errno($ch), curl_error($ch));
    printf("\n\nServer response: \n \n %s \n", $response);
} else {
    curl_close($ch);
    if (!empty($response) && is_string($response)) {
        $result = xmlrpc_decode($response);
        if (is_array($result)) {
            if (xmlrpc_is_fault($result)) {
                if (array_key_exists('faultCode', $result) && array_key_exists('faultString', $result)) {
                    printf("XML-RPC Fault with code  %s: %s \n", (string)$result['faultCode'], $result['faultString']);
                } else {
                    printf("XML_RPC Result Error\n");
                }
            } else {
                printf("\n\nResult from the server: \n %s \n", print_r($result, true));
            }
        } else {
            printf("XML_RPC Decode error\n");
        }
    } else {
        printf("\n\nError: Server return empty response\n\n");
    }
}
