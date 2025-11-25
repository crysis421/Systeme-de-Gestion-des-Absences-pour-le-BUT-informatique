<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES);

    $data = [
        "personalizations" => [[
            "to" => [["email" => "Kilian.Stievenard2@uphf.fr"]],
            "subject" => "Nouveau message du formulaire"
        ]],
        "from" => ["email" => "Christian.EkaniManga@uphf.fr", "name" => "Formulaire de contact"],
        "content" => [[
            "type" => "text/html",
            "value" => "<h3>Nouveau message</h3>
                        <p><strong>Nom :</strong> {$name}</p>
                        <p><strong>Email :</strong> {$email}</p>
                        <p><strong>Message :</strong><br>" . nl2br($message) . "</p>"
        ]]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "le mdp",//TODO
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode == 202) {
        echo "Message envoyé avec succès !";
    } else {
        echo "Erreur lors de l'envoi : HTTP $httpcode - $response";
    }

} else {
    echo "Accès interdit.";
}
?>