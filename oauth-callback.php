<?php


include('includes/config.php');


$authCode = trim($_GET['code']);

$client = new Google_Client();
$client->setApplicationName(APPLICATION_NAME);
$client->setScopes(SCOPES);
$client->setAuthConfig(CLIENT_SECRET_PATH);
$client->setRedirectUri('http://localhost:8000/oauth-callback.php');
$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
$client->setAccessToken($accessToken);

// Refresh the token if it's expired.
if ($client->isAccessTokenExpired()) {
	$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
}

if(!isset($_SESSION['google_access_token'])) {
	$_SESSION['google_access_token'] = $accessToken;
}


$service = new Google_Service_Calendar($client);


$str = file_get_contents('data/nba_schedule_india.json');
$matches = json_decode($str, true);

$from_date = isset($_COOKIE['fromDate']) ? $_COOKIE['fromDate'] : date('Y-m-d');
$to_date = isset($_COOKIE['toDate']) ? $_COOKIE['toDate'] : date('Y-m-d', strtotime('+1 day'));

foreach ($matches as $match) {
    if(strtotime($match['date']) < strtotime($from_date) || strtotime($match['date']) >= strtotime($to_date)) {
        continue;
    }

    $start_time = date_create($match['time'] ." ". $match['date']);
    $calendar_start_time = new \Google_Service_Calendar_EventDateTime();
    $calendar_start_time->setDateTime($start_time->format(\DateTime::RFC3339));

    $end_time = date_create($match['time'] ." ". $match['date'])->modify('+150 minutes');
    $calendar_end_time = new \Google_Service_Calendar_EventDateTime();
    $calendar_end_time->setDateTime($start_time->format(\DateTime::RFC3339));
    $event = new Google_Service_Calendar_Event(array(
        'summary' => $match['htCity']. " " . $match['htNickName'] .  " vs " . $match['vtCity'] . " " . $match['vtNickName'],
        'location' => $match['broadcasterName'],
        'start' => array(
            'dateTime' => $calendar_start_time->dateTime,
            'timeZone' => 'Asia/Kolkata',
        ),
        'end' => array(
            'dateTime' => $calendar_end_time->dateTime,
            'timeZone' => 'Asia/Kolkata',
        ),
        'reminders' => array(
            'useDefault' => TRUE
        )
    ));
    $calendarId = 'primary';
    $event = $service->events->insert($calendarId, $event);
}


header('Location: ' . HOST, true, 302);
die();

