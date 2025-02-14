<?php

namespace Synaptic4U\Core\Encryption\NoEncryption;

use Exception;
use Synaptic4U\Core\DB;
use Synaptic4U\Core\Log;

class SecLink implements ISecLink
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

    public function sendSecInvite($variable = null, $userid = 2)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
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
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
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
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
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
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
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

    protected function log($msg)
    {
        new Log($msg, 'encrypt', 2);
    }

    protected function error($msg)
    {
        new Log($msg, 'error', 2);
    }
}
