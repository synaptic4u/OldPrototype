<?php

namespace Synaptic4U\Packages\Journal\ConfigNotifications;

use Exception;
use Synaptic4U\Core\DB;
use Synaptic4U\Core\Encryption;
use Synaptic4U\Core\Log;

class Model
{
    protected $encrypt;
    protected $db;

    public function __construct()
    {
        

        try {
            $this->encrypt = new Encryption();

            $this->db = new DB();

            $this->log([
                'Location' => __METHOD__.'()',
            ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            return null;
        }
    }

    public function loadRequests($params)
    {
        

        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            $count = 0;

            $sql = 'select count(*) as count
                      from users u
                      join journal_request jr
                        on u.userid = jr.my_userid
                     where u.active = 1
                       and jr.my_userid <> ?
                       and jr.userid = ?;';

            $result = $this->db->query([
                $params['userid'],
                $params['userid'],
            ], $sql);

            foreach ($result as $res) {
                $count = $res->count;
            }

            if (null === $count) {
                throw new Exception('ConfigNotificationsMod->loadRequests() count is null');
            }
            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params),
                'count' => $count,
            ]);

            if ($count > 0) {
                $data['requests'] = $count;

                $sql = 'select u.userid, u.firstname, u.surname, jr.datedon
                          from users u
                          join journal_request jr
                            on u.userid = jr.my_userid
                         where u.active = 1
                           and jr.my_userid <> ?
                           and jr.userid = ?
                         order by u.firstname, u.surname;';

                $result = $this->db->query([
                    $params['userid'],
                    $params['userid'],
                ], $sql);

                foreach ($result as $key => $res) {
                    $data[$key] = [$this->encrypt->scramble($res->userid, 'userid', $params['userid']), $res->firstname, $res->surname, $res->datedon];

                    if (null === $data[$key][0]) {
                        throw new Exception('Encryption failed for userid');
                    }
                }

                $this->log([
                    'Location' => __METHOD__.'()',
                    'params' => json_encode($params),
                    '$res->userid' => $res->userid,
                    'data' => json_encode($data),
                ]);
            } else {
                $data['requests'] = null;
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

    public function acceptRequest($params)
    {
        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        $data = null;

        $acceptid = 0;

        $count = null;

        try {
            $acceptid = (isset($params['formArray'][0])) ?
                $this->encrypt->unscramble($params['formArray'][0], 'userid') :
                0;

            if (null === $acceptid) {
                throw new Exception('Decryption failed for userid');
            }

            if (0 !== ((int) $acceptid)) {
                $sql = 'select count(*) as count 
                          from journal_request 
                         where my_userid = ?
                           and userid = ?';

                $result = $this->db->query([
                    $acceptid,
                    $params['userid'],
                ], $sql);

                foreach ($result as $res) {
                    $count = $res->count;
                }

                if (null === $count) {
                    throw new Exception('ConfigNotificationsMod->acceptRequest() count is null');
                }

                if (((int) $count) > 0) {
                    $sql = 'delete from journal_request where my_userid = ? and userid = ?';

                    $result = $this->db->query([
                        $acceptid,
                        $params['userid'],
                    ], $sql);

                    $data['lastid'] = $this->db->getLastId();

                    if (((int) $data['lastid']) === 0) {
                        $count = null;

                        $sql = 'select count(*) as count 
                                  from journal_shared
                                 where my_userid = ?
                                   and userid = ?';

                        $result = $this->db->query([
                            $acceptid,
                            $params['userid'],
                        ], $sql);

                        foreach ($result as $res) {
                            $count = $res->count;
                        }

                        if (null === $count) {
                            throw new Exception('ConfigNotificationsMod->acceptRequest() count is null');
                        }

                        if (((int) $count) === 0) {
                            $this->log([
                                'Location' => __METHOD__.'()',
                                'params' => json_encode($params),
                                'acceptid' => $acceptid,
                                'count' => $count,
                                '(((int) $count) > 0)' => (((int) $count) > 0),
                            ]);

                            $sql = 'insert into journal_shared(my_userid, userid)values(?, ?)';

                            $result = $this->db->query([
                                $acceptid,
                                $params['userid'],
                            ], $sql);

                            $data['lastid'] = $this->db->getLastId();

                            if (null === $data['lastid']) {
                                throw new Exception('ConfigNotificationsMod->acceptRequest() data[lastid] is null');
                            }

                            $sql = 'select firstname, surname 
                                      from users
                                     where userid = ?';

                            $result = $this->db->query([
                                $acceptid,
                            ], $sql);

                            foreach ($result as $res) {
                                $data['user'] = [$res->firstname, $res->surname];
                            }

                            if (null === $data['user']) {
                                throw new Exception('ConfigNotificationsMod->acceptRequest() data[user] is null');
                            }
                        }
                    } else {
                        $data = null;
                    }
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

    public function callsLoadRequests($params)
    {
        $calls = null;

        try {
            $calls['acceptRequest'] = $this->encrypt->scramble('acceptRequest', 'method', $params['userid']);

            $calls['ConfigNotifications'] = $this->encrypt->scramble('Journal\ConfigNotifications\ConfigNotifications', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['acceptRequest']:
                    throw new Exception('Encryption failed of acceptRequest');

                    break;

                case null === $calls['ConfigNotifications']:
                    throw new Exception('Encryption failed of ConfigNotifications');

                    break;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'calls' => json_encode($calls),
            ]);
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

    public function callsAcceptRequests($params)
    {
        $calls = null;

        try {
            $calls['loadRequests'] = $this->encrypt->scramble('loadRequests', 'method', $params['userid']);

            $calls['ConfigNotifications'] = $this->encrypt->scramble('Journal\ConfigNotifications\ConfigNotifications', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['loadRequests']:
                    throw new Exception('Encryption failed of loadRequests');

                    break;

                case null === $calls['ConfigNotifications']:
                    throw new Exception('Encryption failed of ConfigNotifications');

                    break;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'calls' => json_encode($calls),
            ]);
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
        new Log($msg);
    }
}