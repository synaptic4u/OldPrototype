<?php

namespace Synaptic4U\Core\Encryption;

class Encryption implements IEncryption
{
    public function __construct(IEncryption $encryption)
    {
        $this->encrypt = $encryption;
    }

    public function scramble($variable, $name, $userid = 2, $method = null)
    {
        return $this->encrypt->scramble($variable, $name, $userid, $method);
    }

    public function unscramble($ciphertext, $name, $secTimer = 7200)
    {
        return $this->encrypt->unscramble($ciphertext, $name, $secTimer);
    }

    public function sendPublicKeyPair($userid = 2)
    {
        return $this->encrypt->sendPublicKeyPair($userid);
    }

    public function recievePublicKeyPair($client, $keyid, $string)
    {
        return $this->encrypt->recievePublicKeyPair($client, $keyid, $string);
    }

    public function sendEncrypted($client, $keyid, $string)
    {
        return $this->encrypt->sendEncrypted($client, $keyid, $string);
    }

    public function sendSecInvite($variable = null, $userid = 2)
    {
        return $this->encrypt->sendSecInvite($variable, $userid);
    }

    public function confirmSecInvite($cipher, $secTimer = 7200)
    {
        return $this->encrypt->sendSecInvite($cipher, $secTimer);
    }

    public function sendSecLink($variable = null, $userid = 2)
    {
        return $this->encrypt->sendSecLink($variable, $userid);
    }

    public function confirmSecLink($cipher, $secTimer = 7200)
    {
        return $this->encrypt->confirmSecLink($cipher, $secTimer);
    }

    public function hashString($string)
    {
        return $this->encrypt->hashString($string);
    }

    public function hashStringVerify($hash, $string)
    {
        return $this->encrypt->hashStringVerify($hash, $string);
    }
}
