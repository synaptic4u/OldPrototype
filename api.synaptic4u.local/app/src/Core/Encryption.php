<?php

namespace Synaptic4U\Core;

use Synaptic4U\Core\Encryption\Encryption as EncryptionEncryption;
// use Synaptic4U\Core\Encryption\NoEncryption\NoEncryption;
use Synaptic4U\Core\Encryption\Sodium\Sodium;

class Encryption extends EncryptionEncryption
{
    public function __construct($encryption = null)
    {
        // $encryption = new NoEncryption();
        // Sets it to no encryption used
        // Will Change to $config -> stdClass::class from json file.
        $encryption = (null === $encryption) ? new Sodium() : $encryption;
        parent::__construct($encryption);
    }
}
