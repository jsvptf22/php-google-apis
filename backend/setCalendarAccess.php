<?php
session_start();

require_once '../vendor/autoload.php';

$scopes = [
    Google_Service_Calendar::CALENDAR,
    Google_Service_Drive::DRIVE
];

$_SESSION['service_access'] = [
    'scopes' => $scopes,
    'referer' => 'http://' . $_SERVER['HTTP_HOST'] . '/googleapis'
];

$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/googleapis/backend/getAccess.php';
header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));