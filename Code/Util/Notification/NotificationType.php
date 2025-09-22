<?php

namespace Util\Notification;

use Util\Color\Color;

/*
 * On peut pas assign d'objet mais je trouverai pour associer les differentes notifs à des couleurs spécifiques
 */
enum NotificationType
{
    case ERREUR;
    case VALIDE;
}