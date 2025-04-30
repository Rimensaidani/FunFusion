<?php
require_once '../twilio/twilio-php-main/twilio-php-main/src/Twilio/autoload.php';

use Twilio\Rest\Client;

function sendSMS($phone, $message)
{
    $sid = 'My SID';
    $token = '9cd712fea8d8d3455a17b5c3024e27e6';
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

