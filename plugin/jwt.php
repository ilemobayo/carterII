<?php

// hash secret with your token
// data time string id for expiry date
// issue identifier is a refrence to the website that generate the token


function generateJWTTest(){

    // create token header as JSON string
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

    // create token payload as JSON string
    $payload = json_encode(['user_id' => 123]);

    // Encode header to Base64Url string
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

    // Encode payload to Base64Url string
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

    // Create signature hash
    $signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, 'abc123!', true);

    // Encode Signature to Base64Url string
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    // create JWT
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    return $jwt;
}

function getSecret () {
    return '67ijHByUIaiw9e887e7qq99aiihaaji87$#@@WDRbbbbyyt43247iIJHHGFASSEWRGhju';
}

function generateHeader () {
    // create token header as JSON string
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    // Encode header to Base64Url string
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    return $base64UrlHeader;
}

function genratePayload ($user_id) {
    // create token payload as JSON string
    $payload = json_encode(['user_id' => $user_id]);
    // Encode payload to Base64Url string
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    return $base64UrlPayload;
}

function generateSignature ($base64UrlHeader, $base64UrlPayload, $secret) {
    // Create signature hash
    $signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $secret, true);
    // Encode Signature to Base64Url string
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    return $base64UrlSignature;
}

function getJWT ($user_id){
    $base64UrlHeader = generateHeader ();
    $base64UrlPayload = genratePayload ($user_id);
    $base64UrlSignature = generateSignature ($base64UrlHeader, $base64UrlPayload, getSecret ());
    // create JWT
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    return $jwt;
}


?>