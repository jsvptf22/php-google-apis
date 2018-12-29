<?php
session_start();

require_once '../vendor/autoload.php';

$scopes = [
    Google_Service_Calendar::CALENDAR,
    Google_Service_Drive::DRIVE
];
$client = new Google_Client();
$client->setAuthConfig('../client_secret.json');
$client->setScopes($scopes);

if(isset($_SESSION['service_access']['token']) && $_SESSION['service_access']['token']){
    $client->setAccessToken($_SESSION['service_access']['token']);
    
    $service = new Google_Service_Calendar($client);
    $event = new Google_Service_Calendar_Event([
        'summary' => $_REQUEST['title'] ? $_REQUEST['title'] : 'otro',
        'description' => 'un evento de pruebas',
        'start' => array(
            'dateTime' => '2018-12-18T09:00:00-07:00',
            'timeZone' => 'America/Los_Angeles',
        ),
        'end' => array(
            'dateTime' => '2018-12-18T17:00:00-07:00',
            'timeZone' => 'America/Los_Angeles',
        )
    ]);
        
    $calendarId = 'primary';
    $event = $service->events->insert($calendarId, $event);
    $_SESSION['eventId'] = $event->getId();
    //echo $event->getId();
    
    $driveService = new Google_Service_Drive($client);
    $fileMetadata = new Google_Service_Drive_DriveFile([
        'name' => 'nombre.jpg'
    ]);
    $file = $driveService->files->create($fileMetadata, [
        'data' => file_get_contents($_FILES['media']['tmp_name']),
        'mimeType' => 'image/jpeg',
        'uploadType' => 'media',
        'fields' => 'id'
    ]);

    echo 'se almacena en db y en google';
}else{
    echo 'se almacena en db';
}

