<?php

require_once __DIR__ . '/../vendor/autoload.php';

define('HOST', 'http://localhost:8000/');
define('APPLICATION_NAME', 'Google Calendar API PHP Quickstart');
define('CLIENT_SECRET_PATH', __DIR__ . '/../client_secret.json');
define('SCOPES', implode(' ', array(
  Google_Service_Calendar::CALENDAR)
));


session_start();

?>