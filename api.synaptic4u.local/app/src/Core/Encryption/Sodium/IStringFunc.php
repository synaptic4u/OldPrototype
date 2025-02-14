<?php

namespace Synaptic4U\Core\Encryption\Sodium;

interface IStringFunc
{
    public function hashString($string);

    public function hashStringVerify($hash, $string);
}
