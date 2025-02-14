<?php

namespace Synaptic4U\Core\Encryption\NoEncryption;

use Exception;
use Synaptic4U\Core\DB;
use Synaptic4U\Core\Log;

class StringFunc implements IStringFunc
{
    protected $db;

    public function __construct()
    {
        try {
            $this->db = new DB();

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'db' => serialize($this->db),
            // ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            return null;
        }
    }

    public function hashString($string)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $hash = sodium_crypto_pwhash_str(
            $string,
            SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
            SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
        );

        return str_replace('=', '~~~~', base64_encode($hash));
        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     'hash' => $hash,
        //     'hash2' => $hash2,
        // ]);
    }

    public function hashStringVerify($hash, $string)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $check = 0;

            // ~~ Returns 1 if true, 0 if false
            $hash2 = str_replace('~~~~', '=', $hash);
            $hash3 = base64_decode($hash2);

            $check = sodium_crypto_pwhash_str_verify($hash3, $string);

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'hash' => $hash,
            //     'hash2' => $hash2,
            //     'hash3' => $hash3,
            //     'check' => $check,
            // ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            $check = 0;
        } finally {
            return $check;
        }
    }

    protected function log($msg)
    {
        new Log($msg, 'encrypt', 2);
    }

    protected function error($msg)
    {
        new Log($msg, 'error', 2);
    }
}
