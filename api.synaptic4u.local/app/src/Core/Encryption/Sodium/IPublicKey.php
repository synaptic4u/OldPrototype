<?php

namespace Synaptic4U\Core\Encryption\Sodium;

interface IPublicKey
{
    public function sendPublicKeyPair(int $userid);

    public function recievePublicKeyPair($client, int $keyid, string $string);

    public function sendEncrypted($client, int $keyid, string $string);
}
