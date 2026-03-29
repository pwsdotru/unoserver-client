<?php

declare(strict_types=1);

$url = "http://127.0.0.1:2003"; //Host nad port for Unoserver
$method = "convert"; //check for convert method
$params = [];

if (count($argv) <= 1) {
    printf("Usage file name for convert as argument for command line\n");
    exit(1);
}

$filename = $argv[1] ?? "";

if (empty($filename) || !file_exists($filename)) {
    printf("Error. Filename is incorrect or file %s not exists\n", $filename);
    exit(2);
}

//$params['indata'] = file_get_contents($filename);
$params['indata'] = 'test string';
$params["convert_to"] = "pdf";
$params["outpath"] = null; //"None";

$request = xmlrpc_encode_request($method, $params, ['encoding' => 'UTF-8']);

print_r($request);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    printf("cURL error No %d %s\n", curl_errno($ch), curl_error($ch));
    printf("\n\nServer response: \n \n %s \n", $response);
} else {
    curl_close($ch);
    $result = xmlrpc_decode($response);

    if (xmlrpc_is_fault($result)) {
        printf("XML-RPC Fault with code  %s: %s \n",  (string)$result['faultCode'], $result['faultString']);
    } else {
        printf("\n\nResult from the server: \n %s \n", $result);
    }
}