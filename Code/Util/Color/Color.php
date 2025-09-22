<?php

namespace Util\Color;


/*
 * Classe pour les couleur
 */
class Color
{
    public int $r, $g, $b, $a = 255;

    public final function __construct($r, $g, $b) {
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }

    public final function setAlpha($a): void {
        $this->a = $a;
    }

    public final function setRed($r): void {
        $this->r = $r;
    }

    public final function setGreen($g): void {
        $this->g = $g;
    }

    public final function setBlue($b): void {
        $this->b = $b;
    }

    public function toHex(): string
    {
        return dechex($this->r).dechex($this->g).dechex($this->b);
    }

    public function toRgb(): string
    {
        return $this->r.$this->g.$this->b;
    }
    public function toRgba(): string
    {
        return $this->r.$this->g.$this->b.$this->a;
    }
}