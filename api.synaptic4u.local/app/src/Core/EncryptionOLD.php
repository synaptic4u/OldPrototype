<?php

namespace Synaptic4U\Core;

use Exception;

class EncryptionOLD
{
    protected $db;

    public function __construct()
    {
        try {
            $this->db = new DB();
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
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
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
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
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

    public function sendSecInvite($variable = null, $userid = 2)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     'variable' => $variable,
        //     'userid' => $userid,
        // ]);

        try {
            if (is_null($variable)) {
                for ($i = 0; $i < 5; ++$i) {
                    $variable .= dechex(random_int(0, PHP_INT_MAX));
                }
            }

            $key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
            $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $ciphertext = sodium_crypto_secretbox($variable, $nonce, $key);

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'ciphertext' => bin2hex($ciphertext),
            // ]);

            $ciphertext = bin2hex($ciphertext);

            $sql = 'insert into confirm_login_users(mykey, nonce, cipher, datedon, userid)values(?, ?, ?, now(), ?)';

            $result = $this->db->query([
                bin2hex($key),
                bin2hex($nonce),
                $ciphertext,
                $userid,
            ], $sql, __METHOD__);

            $lastinsertid = $this->db->getLastId();

            if ((int) $lastinsertid > 0) {
                $cipher = $ciphertext;
            } else {
                throw new Exception();
            }
        } catch (Exception $e) {
            $cipher = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $cipher;
        }
    }

    public function confirmSecInvite($cipher, $secTimer = 7200)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
        $data = null;

        // $this->log([
        //     'Location' => __METHOD__.'(): 1',
        //     'cipher' => $cipher,
        //     'base64_decode cipher' => base64_decode($cipher),
        //     'cipher length' => strlen($cipher),
        //     'secTimer' => $secTimer,
        //     'ctype_xdigit($cipher)' => ctype_xdigit($cipher),
        //     'ctype_xdigit($cipher)true/false' => (1 == ctype_xdigit($cipher)) ? true : false,
        // ]);

        try {
            if (ctype_xdigit($cipher)) {
                // $this->log([
                //     'Location' => __METHOD__.'(): 2',
                //     'ctype_xdigit($cipher)' => ctype_xdigit($cipher),
                // ]);

                $sql = 'select count(*) as cnt                
                          from confirm_login_users
                         where cipher = ?';

                $result = $this->db->query([
                    $cipher,
                ], $sql, __METHOD__);

                foreach ($result as $res) {
                    $cnt = $res->cnt;
                }

                // $this->log([
                //     'Location' => __METHOD__.'(): 3',
                //     'cnt' => $cnt,
                // ]);

                if (1 === (int) $cnt) {
                    $sql = 'select userid, 
                                   case when (UNIX_TIMESTAMP(now())-max(UNIX_TIMESTAMP(datedon)) > ?) 
                                   then 1 else 0 end as mydatediff                        
                              from confirm_login_users
                             where cipher = ?';

                    $result = $this->db->query([
                        $secTimer,
                        $cipher,
                    ], $sql, __METHOD__);

                    foreach ($result as $res) {
                        $userid = $res->userid;
                        $mydatediff = $res->mydatediff;
                    }

                    $sql = 'delete from confirm_login_users where cipher = ?';

                    $result = $this->db->query([
                        $cipher,
                    ], $sql, __METHOD__);

                    $rowcnt = null;
                    $rowcnt = $this->db->getrowCount();

                    // $this->log([
                    //     'Location' => __METHOD__.'(): 4',
                    //     'cnt' => $cnt,
                    //     'rowcnt' => $rowcnt,
                    // ]);

                    if (0 === (int) $mydatediff) {
                        $data['approve'] = 1;
                        $data['userid'] = $userid;

                        $sql = 'delete from confirm_login_users where cipher = ?';

                        $result = $this->db->query([
                            $cipher,
                        ], $sql, __METHOD__);
                    } else {
                        $data = [
                            'error' => 'Your link expired',
                            'approve' => 1,
                            'userid' => -100,
                        ];

                        throw new Exception();
                    }
                } else {
                    $data = [
                        'error' => 'The link is invalid',
                        'approve' => 1,
                        'userid' => -100,
                    ];

                    throw new Exception();
                }
            } else {
                $data = [
                    'error' => 'The link is invalid',
                    'approve' => 1,
                    'userid' => -100,
                ];

                throw new Exception();
            }
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    public function sendSecLink($variable = null, $userid = 2)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     'variable' => $variable,
        //     'userid' => $userid,
        // ]);

        try {
            if (is_null($variable)) {
                for ($i = 0; $i < 5; ++$i) {
                    $variable .= dechex(random_int(0, PHP_INT_MAX));
                }
            }

            $key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
            $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $ciphertext = sodium_crypto_secretbox($variable, $nonce, $key);

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'ciphertext' => bin2hex($ciphertext),
            // ]);

            $ciphertext = bin2hex($ciphertext);

            $sql = 'insert into confirm_login_users(mykey, nonce, cipher, datedon, userid)values(?, ?, ?, now(), ?)';

            $result = $this->db->query([
                bin2hex($key),
                bin2hex($nonce),
                $ciphertext,
                $userid,
            ], $sql, __METHOD__);

            $lastinsertid = $this->db->getLastId();

            if ((int) $lastinsertid > 0) {
                $cipher = $ciphertext;
            } else {
                throw new Exception();
            }
        } catch (Exception $e) {
            $cipher = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $cipher;
        }
    }

    public function confirmSecLink($cipher, $secTimer = 7200)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
        $data = null;

        // $this->log([
        //     'Location' => __METHOD__.'(): 1',
        //     'cipher' => $cipher,
        //     'base64_decode cipher' => base64_decode($cipher),
        //     'cipher length' => strlen($cipher),
        //     'secTimer' => $secTimer,
        //     'ctype_xdigit($cipher)' => ctype_xdigit($cipher),
        //     'ctype_xdigit($cipher)true/false' => (1 == ctype_xdigit($cipher)) ? true : false,
        // ]);

        try {
            if (ctype_xdigit($cipher)) {
                // $this->log([
                //     'Location' => __METHOD__.'(): 2',
                //     'ctype_xdigit($cipher)' => ctype_xdigit($cipher),
                // ]);

                $sql = 'select count(*) as cnt                
                          from confirm_login_users
                         where cipher = ?';

                $result = $this->db->query([
                    $cipher,
                ], $sql, __METHOD__);

                foreach ($result as $res) {
                    $cnt = $res->cnt;
                }

                // $this->log([
                //     'Location' => __METHOD__.'(): 3',
                //     'cnt' => $cnt,
                // ]);

                if (1 === (int) $cnt) {
                    $sql = 'select userid, confirmid, datedon,
                                   case when (UNIX_TIMESTAMP(now())-max(UNIX_TIMESTAMP(datedon)) > ?) 
                                   then 1 else 0 end as mydatediff                        
                              from confirm_login_users
                             where cipher = ?';

                    $result = $this->db->query([
                        $secTimer,
                        $cipher,
                    ], $sql, __METHOD__);

                    foreach ($result as $res) {
                        $userid = $res->userid;
                        $mydatediff = $res->mydatediff;
                        $confirmid = $res->confirmid;
                    }

                    $sql = 'delete from confirm_login_users where confirmid = ?';

                    $result = $this->db->query([
                        $confirmid,
                    ], $sql, __METHOD__);

                    $rowcnt = null;
                    $rowcnt = $this->db->getrowCount();

                    // $this->log([
                    //     'Location' => __METHOD__.'(): 4',
                    //     'cnt' => $cnt,
                    //     'rowcnt' => $rowcnt,
                    // ]);

                    if (0 === (int) $mydatediff) {
                        $data = [
                            'approve' => 1,
                            'userid' => $userid,
                        ];

                        $sql = 'delete from confirm_login_users where confirmid = ?';

                        $result = $this->db->query([
                            $confirmid,
                        ], $sql, __METHOD__);
                    } else {
                        $data = [
                            'error' => 'Your link expired',
                            'approve' => -1,
                            'userid' => -100,
                        ];

                        throw new Exception();
                    }
                } else {
                    $data = [
                        'error' => 'The link is invalid',
                        'approve' => -1,
                        'userid' => -100,
                    ];

                    throw new Exception();
                }
            } else {
                $data = [
                    'error' => 'The link is invalid',
                    'approve' => 1,
                    'userid' => -100,
                ];

                throw new Exception();
            }
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    public function hashString($string)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
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
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);

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
