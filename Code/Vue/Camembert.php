<?php

namespace Vue;

class Camembert
{
    public static function afficher($graphe, $nbFois,$titre)
    {
        ?>
        <section id="camembert">
            <h4><?=$titre?></h4>
            <svg width="200" height="200">
                <circle cx="100" cy="100" r="80" fill="none" stroke="#ddd" stroke-width="5"/>
                <?php $cx = 100; // centre
                $cy = 100; // centre
                $r = 88;   // rayon
                $startAngle = 0;

                $color = '000001';
                $colorUtilise[] = '';
                $i = 0;

                foreach ($graphe as $segment) {
                    $i = $i + 1;
                    $value = $segment['count'];
                    $color = dechex((hexdec($color) + hexdec('AE0EAFF')) % hexdec('FFFFFF'));
                    $c = '#' . str_pad($color, 6, '0', STR_PAD_LEFT);
                    array_push($colorUtilise, $c);
                    $angle = $value * 3.5999; // 360° * fraction, pas de 3.6 car ça ne forme pas un cercle complet s'il n'y a qu'une seule absence.

                    $endAngle = $startAngle + $angle;
                    $startX = $cx + $r * cos(deg2rad($startAngle));
                    $startY = $cy + $r * sin(deg2rad($startAngle));
                    $endX = $cx + $r * cos(deg2rad($endAngle));
                    $endY = $cy + $r * sin(deg2rad($endAngle));

                    $largeArcFlag = ($angle > 180) ? 1 : 0;

                    echo "<path d='M$cx,$cy L$startX,$startY A$r,$r 0 $largeArcFlag,1 $endX,$endY Z' fill=$c />";

                    $startAngle = $endAngle;
                } ?>
            </svg>
            <ol>
                    <p id="pourcentage">Nombre d'absences</p>

                <?php foreach ($graphe as $key => $nom) { ?>
                    <li id="li">
                        <div style="width:3px; height:38px; background-color:<?= $colorUtilise[$key + 1] ?>; padding-bottom: 10px;"></div>
                        <p><?= '‎ ' . $nom['label'] ?></p>
                        <p id="pourcentage"><?= round($nbFois[$key + 1]) ?></p>
                    </li>
                <?php } ?>
            </ol>
        </section>
        <?php
    }
}