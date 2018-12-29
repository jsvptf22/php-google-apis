<?php
require_once '../vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig('../client_secret.json');
$client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
  $service = new Google_Service_Drive($client);

    // Print the names and IDs for up to 10 files.
    $optParams = array(
    'pageSize' => 10,
    'fields' => 'nextPageToken, files(id, name)'
    );
    $results = $service->files->listFiles($optParams);

    if (count($results->getFiles()) == 0) {
        print "No files found.\n";
    } else {
        print "Files:\n";
        foreach ($results->getFiles() as $file) {
            printf("%s (%s)\n", $file->getName(), $file->getId());
        }
    }
} else {
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}