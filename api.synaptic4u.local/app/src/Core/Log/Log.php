<?php

namespace Synaptic4U\Core;

class Log
{
    public function __construct($msg, $file = 'activity', $userid = 3)
    {
        // new LogFile($msg, $file, $userid);
        if (!('database' === (string) $file)) {
            if (!('encrypt' === (string) $file)) {
                new LogFile($msg, $file, $userid);
                new LogDB($msg, $file, $userid);
            }
        }
    }
}