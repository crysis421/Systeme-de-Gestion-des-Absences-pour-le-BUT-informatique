<?php

namespace Util\Notification;

use Util\Notification\NotificationType;

class Notification
{
    private NotificationType $type;
    private string $title, $subtitle;
    //Pas de long (peut etre faire une classe chrono)
    private int $timeSec;

    public function __construct(string $title, string $subtitle, int $timeSec, NotificationType $type)
    {
        $this->type = $type;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->timeSec = $timeSec;
    }

    public function render(): void
    {
        /*
         * render notif
         */
    }


    // en fonction du temps
    public function shouldExist():bool
    {
        return true;
    }

    //a changer après je le ferai
    public function getSize(): float
    {
        return 10;
    }

}