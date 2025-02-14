<?php

namespace Synaptic4U\Core\Encryption;

interface IEncryption
{
    public function scramble($variable, $name, $userid, $method);

    public function unscramble($ciphertext, $name, $secTimer);

    public function sendPublicKeyPair($userid);

    public function recievePublicKeyPair($client, $keyid, $string);

    public function sendEncrypted($client, $keyid, $string);

    public function sendSecInvite($variable, $userid);

    public function confirmSecInvite($cipher, $secTimer);

    public function sendSecLink($variable, $userid);

    public function confirmSecLink($cipher, $secTimer);

    public function hashString($string);

    public function hashStringVerify($hash, $string);
}
