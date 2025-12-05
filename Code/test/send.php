<?php

namespace test;

class send
{
    public function __construct()
    {
    }

    function envoyerMailSendGrid($destinataire,$subject, $contentHtml)
    {
        $data = [
            "personalizations" => [[
                "to" => [["email" => $destinataire]],
                "subject" => $subject
            ]],
            "from" => ["email" => "ggestionabsenceuphf@gmail.com", "name" => "Gestion Absence BUT informatique"],
            "content" => [[
                "type" => "text/html",
                "value" => $contentHtml
            ]]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            //mdp
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        /*
        if ($httpcode == 202) {
            echo "Message envoyé avec succès !";
        } else {
            echo "Erreur lors de l'envoi : HTTP $httpcode - $response";
        }
        */

        return ['httpcode' => $httpcode, 'response' => $response];
    }


}