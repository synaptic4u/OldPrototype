<?php

namespace Synaptic4U\Packages\Communication;

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

    public function callsConfirmation($userid)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $calls = null;

        try {
            $calls = [];

            $calls['confirm'] = $this->encrypt->scramble('confirm', 'method', $userid);

            $calls['User'] = $this->encrypt->scramble('User\User', 'controller', $userid);

            $calls['seclink'] = $this->encrypt->sendSecLink(null, $userid);

            $calls['hashUser'] = $this->encrypt->hashString('User');

            $calls['hashConfirm'] = $this->encrypt->hashString('confirm');

            $calls['hashConfirmation'] = $this->encrypt->hashString('confirmation');

            switch (true) {
                case null === $calls['confirm']:
                    throw new Exception('Encryption failed of confirm');

                    break;

                case null === $calls['User']:
                    throw new Exception('Encryption failed of User');

                    break;

                case null === $calls['seclink']:
                    throw new Exception('Encryption failed of seclink');

                    break;

                case null === $calls['hashUser']:
                    throw new Exception('Hashing failed of hashUser');

                    break;

                case null === $calls['hashConfirm']:
                    throw new Exception('Hashing failed of hashConfirm');

                    break;

                case null === $calls['hashConfirmation']:
                    throw new Exception('Hashing failed of hashConfirmation');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__ . '()',
            //     'calls' => json_encode($calls, JSON_PRETTY_PRINT),
            // ]);
        } catch (Exception $e) {
            $calls = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $calls;
        }
    }

    public function sendInvite($inviteid)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            $sql = 'select i.from_userid, i.to_userid, i.email as to_email,
                           concat(u.firstname, " ", u.surname) as from_user, u.email as from_email,
                           concat(uu.firstname, " ", uu.surname) as to_user,
                           case when isnull(a.name) then "null" else a.name end as application, 
                           case when isnull(hd.name) then "null" else hd.name end as hierachyname
                      from invites i
                      join users u
                        on i.from_userid = u.userid
                      join users uu
                        on i.to_userid = uu.userid
                      left join hierachy_det hd
                        on i.hierachyid = hd.hierachyid
                      left join applications a
                        on i.appid = a.appid
                     where i.inviteid = ?;';

            $result = $this->db->query([
                $inviteid,
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data = [
                    'from_userid' => $res->from_userid,
                    'to_userid' => $res->to_userid,
                    'from_email' => $res->from_email,
                    'to_email' => $res->to_email,
                    'from_user' => $res->from_user,
                    'to_user' => $res->to_user,
                    'application' => $res->application,
                    'hierachyname' => $res->hierachyname,
                ];
            }

            $this->log([
                'Location' => __METHOD__.'() : 1',
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            $data = null;
        }

        return $data;
    }

    public function callsSendInvite($from_userid, $to_userid)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['invite'] = $this->encrypt->scramble('invite', 'method', $from_userid);

            $calls['User'] = $this->encrypt->scramble('User\User', 'controller', $from_userid);

            $calls['seclink'] = $this->encrypt->sendSecLink(null, $to_userid);

            $calls['hashUser'] = $this->encrypt->hashString('User');

            $calls['hashInvite'] = $this->encrypt->hashString('invite');

            $calls['hashConfirmation'] = $this->encrypt->hashString('confirmation');

            switch (true) {
                case null === $calls['invite']:
                    throw new Exception('Encryption failed of invite');

                    break;

                case null === $calls['User']:
                    throw new Exception('Encryption failed of User');

                    break;

                case null === $calls['seclink']:
                    throw new Exception('Encryption failed of seclink');

                    break;

                case null === $calls['hashUser']:
                    throw new Exception('Hashing failed of hashUser');

                    break;

                case null === $calls['hashInvite']:
                    throw new Exception('Hashing failed of hashInvite');

                    break;

                case null === $calls['hashConfirmation']:
                    throw new Exception('Hashing failed of hashConfirmation');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__ . '()',
            //     'calls' => json_encode($calls, JSON_PRETTY_PRINT),
            // ]);
        } catch (Exception $e) {
            $calls = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $calls;
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
