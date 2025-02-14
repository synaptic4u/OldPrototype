<?php

namespace Synaptic4U\Core\Encryption\NoEncryption;

use Synaptic4U\Core\Encryption\IEncryption;

class NoEncryption implements IEncryption, ISecLink, IPublicKey, ISecretKey, IStringFunc
{
    public function scramble($variable, $name, $userid = 2, $method = null)
    {
        return $variable;
        $secret = new SecretKey();

        return $secret->scramble($variable, $name, $userid, $method);
    }

    public function unscramble($ciphertext, $name, $secTimer = 7200)
    {
        return $ciphertext;
        $secret = new SecretKey();

        return $secret->unscramble($ciphertext, $name, $secTimer);
    }

    public function sendPublicKeyPair($userid = 2)
    {
        return 'NoEncryption';
        $public = new PublicKey();

        return $public->sendPublicKeyPair($userid);
    }

    public function recievePublicKeyPair($client, $keyid, $string)
    {
        return 'NoEncryption';
        $public = new PublicKey();

        return $public->recievePublicKeyPair($client, $keyid, $string);
    }

    public function sendEncrypted($client, $keyid, $string)
    {
        return 'NoEncryption';
        $public = new PublicKey();

        return $public->sendEncrypted($client, $keyid, $string);
    }

    public function sendSecInvite($variable = null, $userid = 2)
    {
        return 'NoEncryption';
        $link = new SecLink();

        return $link->sendSecInvite($variable, $userid);
    }

    public function confirmSecInvite($cipher, $secTimer = 7200)
    {
        return $cipher;
        $link = new SecLink();

        return $link->sendSecInvite($cipher, $secTimer);
    }

    public function sendSecLink($variable = null, $userid = 2)
    {
        return $variable;
        $link = new SecLink();

        return $link->sendSecLink($variable, $userid);
    }

    public function confirmSecLink($cipher, $secTimer = 7200)
    {
        return $cipher;
        $link = new SecLink();

        return $link->confirmSecLink($cipher, $secTimer);
    }

    public function hashString($string)
    {
        return $string;
        $hash = new StringFunc();

        return $hash->hashString($string);
    }

    public function hashStringVerify($hash, $string)
    {
        return $hash;
        $hash = new StringFunc();

        return $hash->hashStringVerify($hash, $string);
    }
}
