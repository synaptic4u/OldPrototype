<?php

namespace Synaptic4U\Core;

interface LogInterface
{
    public function __construct($msg, $file, $userid, $type);
}