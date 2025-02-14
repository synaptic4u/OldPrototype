<?php

namespace Synaptic4U\Packages\Authenticate;

use Exception;
use Synaptic4U\Core\DB;
use Synaptic4U\Core\Encryption;
use Synaptic4U\Core\Log;
use Synaptic4U\Core\Validate;

class Model
{
    protected $encrypt;
    protected $db;
    protected $validate;

    public function __construct()
    {
        try {
            $this->encrypt = new Encryption();

            $this->db = new DB();

            $path = dirname(__FILE__, 1).'/Models/';

            $this->validate = new Validate($path);

            // $this->log([
            //     'Location' => __METHOD__ . '()',
            //     'Dir' => dirname(__FILE__, 1),
            //     'path' => $path,
            //     'validate' => serialize($this->validate),
            // ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        }
    }

    public function checkUserRole($params)
    {
        $data = [
            'appid' => null,
        ];

        try {
            $sql = 'select appid from application_modules where controller = ? and method = ?;';

            $results = $this->db->query([
                $params['controller'],
                $params['method'],
            ], $sql, __METHOD__);

            foreach ($results as $result) {
                $data = [
                    'appid' => $result->appid,
                ];
            }

            $this->log([
                'Location' => __METHOD__.'() : 1',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $data = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    public function checkRequiredAppPermissions($params)
    {
        $data = [
            'appid' => null,
        ];

        try {
            $sql = 'select appid from application_modules where controller = ? and method = ?;';

            $results = $this->db->query([
                $params['controller'],
                $params['method'],
            ], $sql, __METHOD__);

            foreach ($results as $result) {
                $data = [
                    'appid' => $result->appid,
                ];
            }

            $this->log([
                'Location' => __METHOD__.'() : 1',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $data = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    public function checkUserPermissionOnApp($params)
    {
        $data = null;

        try {
            $sql = '';

            $results = $this->db->query([
                $params['formArray']['email'],
            ], $sql, __METHOD__);

            foreach ($results as $result) {
                $data = [
                    'userid' => $result->userid,
                ];
            }

            $this->log([
                'Location' => __METHOD__.'() : 1',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $data = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    public function checkUserExists($params)
    {
        $data = null;

        try {
            $sql = 'select case when count(userid) = 0 then 0 else userid end as userid, 
                           case when isnull(passkey) then null else passkey end as passkey
                      from users
                     where email = ?;';

            $results = $this->db->query([
                $params['formArray']['email'],
            ], $sql, __METHOD__);

            foreach ($results as $result) {
                $data = [
                    'userid' => $result->userid,
                    'passkey' => $result->passkey,
                ];
            }

            $data['check'] = $this->encrypt->hashStringVerify($data['passkey'], $params['formArray']['passkey']);

            $this->log([
                'Location' => __METHOD__.'() : 1',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $data = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    public function insertLogin($params)
    {
        $data = null;

        try {
            $sql = 'insert into login_users(userid, ip, loggedin';
            $sql .= (!is_null($params['loggedout'])) ? ', loggedout' : '';
            $sql .= ')values(?, ?, '.$params['loggedin'];
            $sql .= (!is_null($params['loggedout'])) ? ', '.$params['loggedout'] : '';
            $sql .= ')';

            $result = $this->db->query([
                $params['userid'],
                $params['ip'],
            ], $sql, __METHOD__);

            $data = $this->db->getLastId();

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => $data,
            ]);
        } catch (Exception $e) {
            $data = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    public function forgotPassword($email)
    {
        $data = null;

        try {
            if (isset($email)) {
                $sql = 'select userid, count(*) as cnt from users where email = ?';

                $result = $this->db->query([
                    $email,
                ], $sql, __METHOD__);

                foreach ($result as $res) {
                    $cnt = $res->cnt;
                    $userid = $res->userid;
                }

                // $this->log([
                //     'Location' => __METHOD__.'(): 0',
                //     'email' => $email,
                //     'cnt' => $cnt,
                //     'userid' => $userid,
                // ]);

                if (1 === (int) $cnt) {
                    $forgotid = 0;

                    $sql = 'insert into users_forgot_password(userid)values(?)';

                    $result = $this->db->query([
                        $userid,
                    ], $sql, __METHOD__);

                    $forgotid = $this->db->getLastId();

                    $this->log([
                        'Location' => __METHOD__.'(): 1',
                        'email' => $email,
                        'cnt' => $cnt,
                        'userid' => $userid,
                        'forgotid' => $forgotid,
                    ]);

                    $data = $this->encrypt->sendSecLink(null, $userid);
                }
            }
        } catch (Exception $e) {
            $data = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    public function confirmSecLink($link)
    {
        $data = null;

        try {
            if (isset($link)) {
                $data = $this->encrypt->confirmSecLink($link);

                $this->log([
                    'Location' => __METHOD__.'(): 0',
                    'link' => $link,
                    'data' => $data,
                ]);
            }
        } catch (Exception $e) {
            $data = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    public function logout($userid)
    {
        $data = null;

        try {
            if (0 < (int) $userid) {
                $sql = 'update login_users 
                           set loggedout = now() 
                         where userid = ?
                           and day(loggedin) = day(now());';

                $result = $this->db->query([
                    $userid,
                ], $sql, __METHOD__);

                $data['rows'] = $this->db->getrowCount();

                // $this->log([
                //     'Location' => __METHOD__.'()',
                //     'userid' => $userid,
                //     'data' => $data['rows'],
                // ]);
            }
        } catch (Exception $e) {
            $data = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    public function userActivity($userid)
    {
        $data = null;

        try {
            $sql = 'select case when isnull(loggedout) then truncate((UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(loggedin))/60, 0) else 0 end as check_activity
                      from login_users
                     where userid = ?
                       and case when isnull(loggedout) then isnull(loggedout) else 1=1 end
                       and loginid = (select max(loginid) from login_users where userid = ?);';

            $result = $this->db->query([
                $userid,
                $userid,
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['time'] = $res->check_activity;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'userid' => $userid,
                'time' => $data['time'],
            ]);
        } catch (Exception $e) {
            $data = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    public function clearUserLogin($limit)
    {
        $data = null;

        try {
            $sql = 'call check_login_user(?)';

            $result = $this->db->callProc([
                $limit,
            ], $sql, __METHOD__);

            $data = $this->db->getStatus();

            $this->log([
                'Location' => __METHOD__.'()',
                'status' => $data,
            ]);
        } catch (Exception $e) {
            $data = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    public function getWhoAmI($userid)
    {
        $data = null;

        try {
            $sql = 'select count(*) as count
                      from hierachy_users 
                     where userid = ?';

            $results = $this->db->query([
                $userid,
            ], $sql, __METHOD__);

            foreach ($results as $result) {
                $data['hierachy_count'] = $result->count;
            }

            $sql = 'select count(*) as count
                      from application_users 
                     where userid = ?';

            $results = $this->db->query([
                $userid,
            ], $sql, __METHOD__);

            foreach ($results as $result) {
                $data['app_count'] = $result->count;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'userid' => $userid,
                'data' => $data,
            ]);
        } catch (Exception $e) {
            $data = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    protected function error($msg)
    {
        new Log($msg, 'error');
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }
}
