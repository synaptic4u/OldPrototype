<?php

namespace Synaptic4U\Core\Encryption\Sodium;

use Exception;
use Synaptic4U\Core\DB;
use Synaptic4U\Core\Log;

class PublicKey implements IPublicKey
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

    public function sendPublicKeyPair($userid = 2)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);

        try {
            // ~~	House cleaning
            $this->db->cleanmykeys();

            // ~~	Encrypt & Save
            $keyid = 0;
            $serverKeyPair = sodium_crypto_box_keypair();
            $serverPublicKey = sodium_crypto_box_publickey($serverKeyPair);
            $serverPrivateKey = sodium_crypto_box_secretkey($serverKeyPair);

            $query = 'insert into mykeys(userid, keypair, publickey, secretkey, datedon)values(?,?,?,?,now())';

            $params = [
                $userid,
                bin2hex($serverKeyPair),
                bin2hex($serverPublicKey),
                bin2hex($serverPrivateKey),
            ];

            $results = $this->db->query($params, $query, __METHOD__);

            $keyid = $this->db->getLastId();

            // $this->log([
            //     'Location' => __METHOD__.'(): 1',
            //     'keyid' => $keyid,
            //     'serverKeyPair' => bin2hex($serverKeyPair),
            //     'serverKeyPair length' => strlen(bin2hex($serverKeyPair)),
            //     'serverPublicKey' => bin2hex($serverPublicKey),
            //     'serverPublicKey length' => strlen(bin2hex($serverPublicKey)),
            //     'serverPrivateKey' => bin2hex($serverPrivateKey),
            //     'serverPrivateKey length' => strlen(bin2hex($serverPrivateKey)),
            // ]);

            $result = [
                'serverPublicKey' => explode(',', implode(',', unpack('C*', $serverPublicKey))),
                'serverPrivateKey' => explode(',', implode(',', unpack('C*', $serverPrivateKey))),
                'keyid' => $keyid,
            ];

            // $this->log([
            //     'Location' => __METHOD__.'(): 2',
            //     'serverPublicKey' => json_encode($result['serverPublicKey']),
            //     'serverPrivateKey' => json_encode($result['serverPrivateKey']),
            //     'keyid' => $result['keyid'],
            // ]);
        } catch (Exception $e) {
            $result = [
                'serverPublicKey' => null,
                'keyid' => null,
            ];

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $result;
        }
    }

    public function recievePublicKeyPair($client, $keyid, $string)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
        // $this->log([
        //     'Location' => __METHOD__.'(): 1',
        //     'SODIUM_CRYPTO_BOX_NONCEBYTES' => SODIUM_CRYPTO_BOX_NONCEBYTES,
        //     'clientIsArray' => (is_array($client[0])) ? 'true' : 'false',
        //     'client' => $client,
        //     'clientArray' => json_encode($client),
        //     'clientString' => implode(',', [$client]),
        //     'client2' => pack('C*', ...explode(',', [$client][0])),
        //     'keyid' => $keyid,
        //     'string' => $string,
        // ]);

        try {
            $query = 'select keyid, secretkey, datedon 
						from mykeys 
					   where keyid=?';

            $params = [
                $keyid,
            ];

            $results = $this->db->query($params, $query, __METHOD__);

            foreach ($results as $result) {
                $ServerSecretKey = $result->secretkey;
                $datedon = $result->datedon;
            }

            // ~~	Hex to bin
            $ClientPublicKeyBin = pack('C*', ...explode(',', [$client][0]));

            // ~~	get nonce
            $nonce = mb_substr($string, 0, 24, '8bit');

            // ~~	get cypher
            $cypher = mb_substr($string, 24, null, '8bit');

            // ~~	concatenate ServerSecretKey with ClientPublicKey
            $key = hex2bin($ServerSecretKey).$ClientPublicKeyBin;

            // $this->log([
            //     'Location' => __METHOD__.'(): 2',
            //     'SODIUM_CRYPTO_BOX_NONCEBYTES' => SODIUM_CRYPTO_BOX_NONCEBYTES,
            //     'clientIsArray' => (is_array($client)) ? 'true' : 'false',
            //     'client' => $client,
            //     'clientArray' => json_encode($client),
            //     'clientString' => implode(',', [$client]),
            //     'client2' => pack('C*', ...explode(',', [$client][0])),
            //     'keyid' => $keyid,
            //     'string' => $string,
            //     'datedon' => $datedon,
            //     'ClientPublicKeyBin' => $ClientPublicKeyBin,
            //     'nonce' => $nonce,
            //     'cypher' => $cypher,
            //     'key' => $key,
            // ]);

            // ~~	Open
            $opentext = sodium_crypto_box_open($cypher, $nonce, $key);

            // $this->log([
            //     'Location' => __METHOD__.'(): 3',
            //     'cypher' => $cypher,
            //     'nonce' => $nonce,
            //     'key' => $key,
            //     'opentext' => $opentext,
            // ]);
        } catch (Exception $e) {
            $opentext = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $opentext;
        }
    }

    public function sendEncrypted($client, $keyid, $string)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);

        try {
            $ServerPublicKey = null;
            $ServerSecretKey = null;
            $ServerKeyPair = null;

            $query = 'select keyid, secretkey, datedon 
						from mykeys 
					   where keyid=?';

            $params = [
                $keyid,
            ];

            $results = $this->db->query($params, $query, __METHOD__);

            foreach ($results as $result) {
                $ServerSecretKey = $result->secretkey;
            }

            $ClientPublicKeyBin = pack('C*', ...explode(',', [$client][0]));

            // ~~ get nonce
            $nonce = random_bytes(SODIUM_CRYPTO_BOX_NONCEBYTES); // openssl_random_pseudo_bytes(24);

            // ~~ concatenate ServerSecretKey with ClientPublicKey
            $key = hex2bin($ServerSecretKey).$ClientPublicKeyBin;

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'client' => $client,
            //     'clientIsArray' => (is_array($client)) ? 'true' : 'false',
            //     'ClientPublicKeyBin' => $ClientPublicKeyBin,
            //     'keyid' => $keyid,
            //     'string' => $string,
            //     'nonce' => $nonce,
            //     'key' => $key,
            // ]);

            // ~~ Close
            $reply = $nonce.sodium_crypto_box($string, $nonce, $key);
            // ~~ $reply = base64_encode($reply);
            $reply = sodium_bin2base64($reply, SODIUM_BASE64_VARIANT_ORIGINAL);
        } catch (Exception $e) {
            $reply = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $reply;
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
