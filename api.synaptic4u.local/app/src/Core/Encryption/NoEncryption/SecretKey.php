<?php

namespace Synaptic4U\Core\Encryption\NoEncryption;

use Exception;
use Synaptic4U\Core\DB;
use Synaptic4U\Core\Log;

class SecretKey implements ISecretKey
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

    public function scramble($variable, $name, $userid = 2, $method = null)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        // $this->log([
        //     'Location' => __METHOD__.'(): 1',
        //     'method' => $method,
        //     'name' => $name,
        //     'variable' => $variable,
        //     'userid' => $userid,
        // ]);

        try {
            $key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
            $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $ciphertext = sodium_crypto_secretbox($variable, $nonce, $key);

            // $this->log([
            //     'Location' => __METHOD__.'(): 2',
            //     'ciphertext' => $ciphertext,
            //     'base64_encode->ciphertext' => base64_encode($ciphertext),
            //     'bin2hex($ciphertext)' => bin2hex($ciphertext),
            // ]);

            $ciphertext = bin2hex($ciphertext);

            // $this->log([
            //     'Location' => __METHOD__.'(): 3',
            //     'key' => bin2hex($key),
            //     'nonce' => bin2hex($nonce),
            //     'cipher' => bin2hex($ciphertext),
            // ]);

            $sql = 'insert into stream(userid, datedon, nonce, mykey)values(?, now(), ?, ?)';

            $result = $this->db->query([
                $userid,
                bin2hex($nonce),
                bin2hex($key),
            ], $sql, __METHOD__);
            $lastinsertid = $this->db->getLastId();

            $sql = 'select streamid, datedon 
					  from stream 
					 where mykey = ? 
					   and nonce = ? 
					   and userid = ?';
            $params = [
                bin2hex($key),
                bin2hex($nonce),
                $userid,
            ];
            $results = $this->db->query($params, $sql, __METHOD__);

            foreach ($results as $result) {
                $streamid = $result->streamid;
                $datedon = $result->datedon;
            }

            // $this->log([
            //     'Location' => __METHOD__.'(): 4',
            //     'streamid' => $streamid,
            //     'lastinsertid' => $lastinsertid,
            //     'datedon' => $datedon,
            //     'streamidDec2Hex' => dechex($streamid),
            // ]);

            $streamid = dechex($streamid);

            $padding = sha1($name);
            $padding2 = sha1(md5($name));
            $datedon2 = dechex(strtotime($datedon));

            $string = $streamid.''.$padding.''.$datedon2.''.$padding2;

            $cipher = $string.''.$ciphertext;

            // $this->log([
            //     'Location' => __METHOD__.'(): 5',
            //     'datedon2' => $datedon2,
            //     'strtotime($datedon)' => strtotime($datedon),
            //     '$padding' => $padding,
            //     'strlen($padding)' => strlen($padding),
            //     '$padding2' => $padding2,
            //     'strlen($padding2)' => strlen($padding2),
            //     '$string' => $streamid.''.$padding.''.$datedon2.''.$padding2,
            //     '$ciphertext' => $ciphertext,
            //     '$cipher' => $string.''.$ciphertext,
            //     '$cipher is ctype_xdigit' => ctype_xdigit($cipher),
            //     'ctype_xdigit($ciphertext)true/false' => (1 == ctype_xdigit($ciphertext)) ? true : false,
            // ]);

            sodium_memzero($name);
            sodium_memzero((string) $variable);
            sodium_memzero($key);
            sodium_memzero($nonce);
            sodium_memzero($ciphertext);
            sodium_memzero($streamid);
            sodium_memzero($padding);
            sodium_memzero($datedon2);
            sodium_memzero($padding2);
            sodium_memzero($string);
        } catch (Exception $e) {
            $cipher = sodium_memzero($cipher);
            $cipher = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $cipher;
        }
    }

    public function unscramble($ciphertext, $name, $secTimer = 7200)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $ciphertext = trim($ciphertext);
        $name = trim($name);

        // $this->log([
        //     'Location' => __METHOD__.'(): 1',
        //     'ciphertext' => $ciphertext,
        //     'name' => $name,
        //     'secTimer' => $secTimer,
        //     'ctype_xdigit($ciphertext)' => ctype_xdigit($ciphertext),
        //     'ctype_xdigit($ciphertext)true/false' => ((int) 1 === (int) ctype_xdigit($ciphertext)) ? 'true' : 'false',
        // ]);

        try {
            if (ctype_xdigit($ciphertext)) {
                $this->log([
                    'Location' => __METHOD__.'(): 2',
                    'ctype_xdigit($ciphertext)' => ctype_xdigit($ciphertext),
                ]);

                $padding = sha1($name);
                $padding2 = sha1(md5($name));

                $paddingpos = strpos($ciphertext, $padding);
                $padding2pos = strpos($ciphertext, $padding2);

                $streamid = substr($ciphertext, 0, $paddingpos);

                $paddingcheck = substr($ciphertext, strlen($streamid), strlen($padding));

                // $this->log([
                //     'Location' => __METHOD__.'(): 3',
                //     '$padding' => sha1($name),
                //     '$padding2' => sha1(md5($name)),
                //     '$paddingpos' => strpos($ciphertext, $padding),
                //     '$padding2pos' => strpos($ciphertext, $padding2),
                //     '$streamid' => substr($ciphertext, 0, $paddingpos),
                //     '$paddingcheck' => substr($ciphertext, strlen($streamid), strlen($padding)),
                // ]);

                if ($paddingcheck === $padding) {
                    $this->log([
                        'Location' => __METHOD__.'(): 4',
                        '$paddingcheck === $padding' => $paddingcheck.' === '.$padding,
                    ]);

                    $datedon = substr($ciphertext, strlen($streamid) + strlen($paddingcheck), $padding2pos - strlen($streamid) - strlen($paddingcheck));

                    $padding2check = substr($ciphertext, strpos($ciphertext, $padding.''.$datedon) + strlen($padding.''.$datedon), strlen($padding2));

                    if ($padding2 === $padding2check) {
                        // $this->log([
                        //     'Location' => __METHOD__.'(): 5',
                        //     '$padding2 === $padding2check' => $padding2.' === '.$padding2check,
                        //     'ciphertext' => $ciphertext,
                        //     '$padding2pos' => $padding2pos,
                        //     'strlen($padding2check)' => strlen($padding2check),
                        //     'strlen($ciphertext))' => strlen($ciphertext),
                        //     'cipher' => substr($ciphertext, $padding2pos + strlen($padding2check), strlen($ciphertext)),
                        // ]);

                        $cipher = substr($ciphertext, $padding2pos + strlen($padding2check), strlen($ciphertext));

                        $streamid = substr($ciphertext, 0, $paddingpos);
                        $streamid = hexdec($streamid);
                        $datedon = hexdec($datedon);

                        // $this->log([
                        //     'Location' => __METHOD__.'(): 6',
                        //     '$streamid' => $streamid,
                        //     'hexdec($streamid)' => dechex($streamid),
                        //     '$datedon' => $datedon,
                        //     'dechex($datedon)' => dechex($datedon),
                        // ]);

                        $sql = 'select case when exists(select streamid 
														 from stream 
														where streamid = ? 
														  and UNIX_TIMESTAMP(datedon) = ? ) = 1 
											then 1 else 0 end as Res;';

                        $results = $this->db->query([
                            $streamid,
                            $datedon,
                        ], $sql, __METHOD__);

                        foreach ($results as $result) {
                            $exists = $result->Res;
                        }

                        // $this->log([
                        //     'Location' => __METHOD__.'(): 7',
                        //     '$exists' => $exists,
                        // ]);

                        if (1 === (int) $exists) {
                            // $this->log([
                            //     'Location' => __METHOD__.'(): 8',
                            //     '(int)$exists === 1' => (1 === (int) $exists) ? 'Yes' : 'No',
                            // ]);

                            // ~~ Get Key, nonce
                            $sql = 'select streamid, mykey, nonce,
										   case when (UNIX_TIMESTAMP(now())-max(UNIX_TIMESTAMP(datedon)) > ?) 
										 		then 1 else 0 end as MyDateDiff
                                      from stream 
                                     where streamid = ?
                                       and UNIX_TIMESTAMP(datedon) = ?';

                            $results = $this->db->query([
                                $secTimer,
                                $streamid,
                                $datedon,
                            ], $sql, __METHOD__);

                            // $this->log([
                            //     'Location' => __METHOD__.'(): 9',
                            //     '$secTimer' => $secTimer,
                            //     '$streamid' => $streamid,
                            //     '$datedon' => $datedon,
                            // ]);

                            $streamidcheck = 0;
                            $mykey = '';
                            $nonce = '';
                            $mydatediff = 0;

                            foreach ($results as $result) {
                                $streamidcheck = $result->streamid;
                                $mykey = $result->mykey;
                                $nonce = $result->nonce;
                                $mydatediff = $result->MyDateDiff;
                            }

                            // $this->log([
                            //     'Location' => __METHOD__.'(): 10',
                            //     '$streamidcheck' => $streamidcheck,
                            //     '$mykey' => $mykey,
                            //     '$nonce' => $nonce,
                            //     '$mydatediff' => $mydatediff,
                            //     '$cipher' => $cipher,
                            // ]);

                            // ~~	Check time length
                            if (((int) $mydatediff > 0) && ($streamidcheck > 0) && (48 != strlen($nonce)) && (64 != strlen($mykey))) {
                                throw new Exception();
                            }

                            if (sodium_crypto_secretbox_open(hex2bin($cipher), hex2bin($nonce), hex2bin($mykey))) {
                                $plaintext = sodium_crypto_secretbox_open(hex2bin($cipher), hex2bin($nonce), hex2bin($mykey));

                                // $this->log([
                                //     'Location' => __METHOD__.'(): 11',
                                //     'Unscramble() plaintext' => $plaintext,
                                // ]);

                                return trim($plaintext);
                            }

                            throw new Exception();
                        } else {
                            throw new Exception();
                        }
                    } else {
                        throw new Exception();
                    }
                } else {
                    throw new Exception();
                }
            } else {
                throw new Exception();
            }

            sodium_memzero($ciphertext);
            sodium_memzero($cipher);
            sodium_memzero($mykey);
            sodium_memzero($nonce);
            sodium_memzero($streamidcheck);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            $plaintext = null;
        } finally {
            return $plaintext;
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
