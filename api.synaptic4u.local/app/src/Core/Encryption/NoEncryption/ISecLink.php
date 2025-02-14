<?php

namespace Synaptic4U\Core\Encryption\NoEncryption;

interface ISecLink
{
    public function sendSecInvite($variable, int $userid);

    public function confirmSecInvite($cipher, int $secTimer);

    public function sendSecLink($variable, int $userid);

    public function confirmSecLink($cipher, int $secTimer);
}
