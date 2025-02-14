<?php

namespace Synaptic4U\Core\Encryption\Sodium;

interface ISecretKey
{
    public function scramble($variable, $name, $userid, $method);

    public function unscramble($ciphertext, $name, $secTimer);
}
