<?php
session_start();

require_once '../vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfigFile('../client_secret.json');

$returnUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/googleapis/backend/getAccess.php';
$client->setRedirectUri($returnUrl);
$client->setScopes($_SESSION['service_access']['scopes']);

if (! isset($_GET['code'])) {
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
    $client->authenticate($_GET['code']);
    $_SESSION['service_access']['token'] = $client->getAccessToken();

    header('Location: ' . filter_var($_SESSION['service_access']['referer'], FILTER_SANITIZE_URL));
}