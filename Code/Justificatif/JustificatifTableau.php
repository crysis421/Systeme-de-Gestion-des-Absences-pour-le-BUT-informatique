<?php


?>



<!DOCTYPE html>
<html lang="fr">
<link rel="stylesheet" href="Justificatif.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des absences - Justificatif</title>

</head>
<body>

<div class="onglets">
    <div class="onglet actif">Tableau de bord</div>
    <div class="onglet">Historique des décisions</div>
    <div class="onglet">...</div>
    <!-- je mettrai après en bien ! -->
    <hr/>
</div>

<div class="container">
    <h1>Absences :</h1>

    <div class="absence-list">
        <h2>Liste des absences</h2>

        <!-- rendu de chaque item avec php à la place de ça ! -->
        <div class="absence-item">
            <div class="absence-header">
                <div class="profile-icon">👤</div>
                <div class="name">NOM Prénom</div>
                <button class="expand-btn">∨</button>
            </div>

            <div class="absence-details">
                <div class="justificatif-line">
                    <span>Justificatif</span>
                    <span class="status-icons">
                            <span class="status-icon green-check"></span>
                            <span class="status-icon red-x"></span>
                            <span class="status-icon red-x"></span>
                        </span>
                </div>

                <div class="decision-line">
                    <span>Décision finale</span>
                    <span class="status-icons">
                            <span class="status-icon green-check"></span>
                            <span class="status-icon red-x"></span>
                            <span class="status-icon red-x"></span>
                        </span>
                </div>

                <div class="form-controls">
                    <select class="reason-select">
                        <option>Raison...</option>
                        <option>Transport</option>
                        <option>Malade</option>
                        <option>Erreur d'EDT</option>
                    </select>
                    <button class="send-btn">Envoyer</button>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>