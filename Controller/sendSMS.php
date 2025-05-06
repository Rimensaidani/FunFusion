<?php
require_once '../twilio/twilio-php-main/twilio-php-main/src/Twilio/autoload.php';

use Twilio\Rest\Client;

function sendSMS($phone, $message)
{
    $sid = 'sid';
    $token = 'token';
    $client = new Client($sid, $token);

    $to = '+216' . $phone; 

    $client->messages->create(
        $to, 
        [
            'from' => '+1 270 515 6645', 
            'body' => $message
        ]
    );
}
?>

