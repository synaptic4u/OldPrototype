<?php

namespace Synaptic4U\Core\Encryption\Sodium;

use Synaptic4U\Core\Encryption\IEncryption;

class Sodium implements IEncryption, ISecLink, IPublicKey, ISecretKey, IStringFunc
{
    public function scramble($variable, $name, $userid = 2, $method = null)
    {
        return (new SecretKey())->scramble($variable, $name, $userid, $method);
    }

    public function unscramble($ciphertext, $name, $secTimer = 7200)
    {
        return (new SecretKey())->unscramble($ciphertext, $name, $secTimer);
    }

    public function sendPublicKeyPair($userid = 2)
    {
        return (new PublicKey())->sendPublicKeyPair($userid);
    }

    public function recievePublicKeyPair($client, $keyid, $string)
    {
        return (new PublicKey())->recievePublicKeyPair($client, $keyid, $string);
    }

    public function sendEncrypted($client, $keyid, $string)
    {
        return (new PublicKey())->sendEncrypted($client, $keyid, $string);
    }

    public function sendSecInvite($variable = null, $userid = 2)
    {
        return (new SecLink())->sendSecInvite($variable, $userid);
    }

    public function confirmSecInvite($cipher, $secTimer = 7200)
    {
        return (new SecLink())->sendSecInvite($cipher, $secTimer);
    }

    public function sendSecLink($variable = null, $userid = 2)
    {
        return (new SecLink())->sendSecLink($variable, $userid);
    }

    public function confirmSecLink($cipher, $secTimer = 7200)
    {
        return (new SecLink())->confirmSecLink($cipher, $secTimer);
    }

    public function hashString($string)
    {
        return (new StringFunc())->hashString($string);
    }

    public function hashStringVerify($hash, $string)
    {
        return (new StringFunc())->hashStringVerify($hash, $string);
    }
}
