<?php

namespace App\Http\Controllers;

use Config;

class StreamableController extends Controller
{
    protected static function sendPacket($Id, $Packet)
    {
        echo "id: " . $Id . "\n";
        echo "data: " . json_encode($Packet) . "\n\n";
        ob_flush();
        flush();
    }
}
