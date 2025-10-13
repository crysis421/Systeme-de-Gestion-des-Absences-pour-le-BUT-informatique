<?php

require '../../mailjet-apiv3-php-no-composer-master/src/Mailjet/autoload.php';

use \Mailjet\Resources;

// Use your saved credentials, specify that you are using Send API v3.1

$mj = new \Mailjet\Client(getenv('MJ_APIKEY_PUBLIC'), getenv('MJ_APIKEY_PRIVATE'), true, ['version' => 'v3.1']);

// Define your request body

$body = [
    'Messages' => [
        [
            'From' => [
                'Email' => "Kilian.Stievenard2@uphf.fr",
                'Name' => "Me"
            ],
            'To' => [
                [
                    'Email' => "Kilian.Stievenard2@uphf.fr",
                    'Name' => "You"
                ]
            ],
            'Subject' => "My first Mailjet Email!",
            'TextPart' => "Greetings from Mailjet!",
            'HTMLPart' => "<h3>Dear passenger 1, welcome to <a href=\"https://www.mailjet.com/\">Mailjet</a>!</h3>
            <br />May the delivery force be with you!"
        ]
    ]
];

// All resources are located in the Resources class

$response = $mj->post(Resources::$Email, ['body' => $body]);

// Read the response

$response->success() && var_dump($response->getData());
