<?php


namespace App\Service;


class Helper
{
    public function humanSize(float $bytes, int $decimals = 2): string
    {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');

        $factor = floor((strlen(strval($bytes)) - 1) / 3);

        return sprintf("%.{$decimals}f", floatval($bytes) / pow(1024, $factor)) . ' ' . @$size[$factor];
    }
}