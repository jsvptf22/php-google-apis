<?php
require_once '../vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig('../client_secret.json');
$client->addScope(Google_Service_Calendar::CALENDAR);

if(!isset($_SESSION['eventId'])){

  if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
    
    $service = new Google_Service_Calendar($client);
    
    $event = new Google_Service_Calendar_Event(array(
      'location' => 'un lugar cualquiera',
      'description' => 'un evento de pruebas',
      'start' => array(
        'dateTime' => '2018-12-10T09:00:00-07:00',
        'timeZone' => 'America/Los_Angeles',
      ),
      'end' => array(
        'dateTime' => '2018-12-10T17:00:00-07:00',
        'timeZone' => 'America/Los_Angeles',
        )
      ));
      
      $calendarId = 'primary';
      $event = $service->events->insert($calendarId, $event);
      $_SESSION['eventId'] = $event->getId();
      echo $event->getId();
      
    } else {
      $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
      header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    }
}else{
  $client->setAccessToken($_SESSION['access_token']);
  $service = new Google_Service_Calendar($client);

  $service->events->delete('primary', $_SESSION['eventId']);
  echo $_SESSION['eventId'] . " eliminado";
}