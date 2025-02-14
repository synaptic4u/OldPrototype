<?php

namespace Synaptic4U\Core\Encryption\NoEncryption;

interface IStringFunc
{
    public function hashString($string);

    public function hashStringVerify($hash, $string);
}
