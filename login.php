<?php
require 'vendor/autoload.php'; // Ensure you have installed the Magic SDK using Composer

use MagicAdmin\Magic;
use MagicAdmin\Exception\DIDTokenException;

// Initialize Magic with your Secret Key
$magic = new Magic('sk_live_61CADD74E2C7565F'); // Replace with your actual Magic Secret Key

// Set the appropriate header for JSON response
header('Content-Type: application/json');

// Start the session
session_start();

try {
    // Retrieve the DID token from the POST request
    $didToken = $_POST['token'] ?? '';

    if (empty($didToken)) {
        throw new Exception('Missing Magic token.');
    }

    // Validate the DID token
    $token = $magic->token->validate($didToken);

    // Decode the DID token to retrieve user info
    $decodedToken = $magic->token->decode($didToken);

    // Retrieve the issuer and public address from the token
    $issuer = $magic->token->get_issuer($didToken);
    $publicAddress = $magic->token->get_public_address($didToken);

    // Fetch user metadata using the issuer
    $userInfo = $magic->user->get_metadata_by_issuer($issuer);

    if (!$userInfo) {
        throw new Exception('User metadata not found.');
    }

    // Store user information in the session
    $_SESSION['user'] = [
        'metadata' => $userInfo,
        'issuer' => $issuer,
        'public_address' => $publicAddress,
        'decoded' => $decodedToken
    ];

    // Respond with user data
    echo json_encode([
        'authenticated' => true,
        'user' => $userInfo,
        'issuer' => $issuer,
        'public_address' => $publicAddress,
        'decoded' => $decodedToken
    ]);
} catch (DIDTokenException $me) {
    // Handle Magic-specific exceptions
    http_response_code(400);
    echo json_encode(['authenticated' => false, 'error' => $me->getMessage()]);
} catch (Exception $e) {
    // Handle general exceptions
    http_response_code(400);
    echo json_encode(['authenticated' => false, 'error' => $e->getMessage()]);
}
