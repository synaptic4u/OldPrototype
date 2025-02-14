<?php

namespace Synaptic4U\Packages\Journal\ConfigSharing;

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

    public function shareable($params)
    {
        

        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            $sql = 'select count(*) as count from journal_sharing where userid = ?;';

            $result = $this->db->query([
                $params['userid'],
            ], $sql);

            foreach ($result as $res) {
                $cnt = $res->count;
            }

            if ($cnt > 0) {
                $sql = 'select sharing
                          from journal_sharing
                         where userid = ?';

                $result = $this->db->query([
                    $params['userid'],
                ], $sql);

                foreach ($result as $res) {
                    $data['sharing'] = $res->sharing;
                }

                if (null === $data['sharing']) {
                    throw new Exception('User sharing returned null');
                }
            } else {
                $data['sharing'] = 0;
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

    public function shared($params)
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
                      join journal_sharing js
                        on u.userid = js.userid
                     where u.userid not in (select userid from journal_request where my_userid = ?)
                       and u.userid not in (select userid from journal_shared where my_userid = ?)
                       and js.sharing = 1
                       and u.active = 1
                       and u.userid <> ?;';

            $result = $this->db->query([
                $params['userid'],
                $params['userid'],
                $params['userid'],
            ], $sql);

            foreach ($result as $res) {
                $count = $res->count;
            }

            if (null === $count) {
                throw new Exception('Count returned null');
            }

            if ($count > 0) {
                $data['sharing'] = $count;

                $sql = 'select u.userid, u.firstname, u.surname
                          from users u
                          join journal_sharing js
                            on u.userid = js.userid
                         where u.userid not in (select userid from journal_request where my_userid = ?)
                           and u.userid not in (select userid from journal_shared where my_userid = ?)
                           and js.sharing = 1
                           and u.active = 1
                           and u.userid <> ?
                         order by u.firstname, u.surname;';

                $result = $this->db->query([
                    $params['userid'],
                    $params['userid'],
                    $params['userid'],
                ], $sql);

                foreach ($result as $key => $res) {
                    $data[$key] = [$this->encrypt->scramble($res->userid, 'userid', $params['userid']), $res->firstname, $res->surname];

                    if (null === $data[$key][0]) {
                        throw new Exception('Encryption failed for userid');
                    }
                }
            } else {
                $data['sharing'] = null;
                $data['count'] = 0;
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

    public function store($params)
    {
        
        $data = null;

        try {
            if (isset($params['formArray']['shareableRadio'])) {
                $sharing = (
                    (1 === (int) $params['formArray']['shareableRadio'])
                        || (0 === (int) $params['formArray']['shareableRadio'])
                ) ? $params['formArray']['shareableRadio'] : null;

                if (null === $sharing) {
                    throw new Exception('Sharing null');
                }
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params),
                'sharing' => $sharing,
            ]);

            if (null !== $sharing) {
                $sql = 'select count(*) as count from journal_sharing where userid = ?';

                $result = $this->db->query([
                    $params['userid'],
                ], $sql);

                foreach ($result as $res) {
                    $cnt = $res->count;
                }

                if ($cnt > 0) {
                    $sql = 'update journal_sharing 
                               set sharing = ?
                             where userid = ?';

                    $result = $this->db->query([
                        $sharing,
                        $params['userid'],
                    ], $sql);
                } else {
                    $sql = 'insert into journal_sharing(userid, sharing)values(?, ?);';

                    $result = $this->db->query([
                        $params['userid'],
                        $sharing,
                    ], $sql);
                }

                $data['sharing'] = $sharing;
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

    public function request($params)
    {
        
        $data = null;

        $requestid = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            if (isset($params['formArray'])) {
                foreach ($params['formArray'] as $param) {
                    if (strlen($param) > 1) {
                        $requestid = $this->encrypt->unscramble($param, 'userid');

                        if (null === $requestid) {
                            throw new Exception('Decryption failed for user requestid');
                        }
                    }
                }
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params),
                'requestid' => $requestid,
            ]);

            if (null !== $requestid) {
                $sql = 'insert into journal_request(my_userid, userid)values(?, ?)';

                $result = $this->db->query([
                    $params['userid'],
                    $requestid,
                ], $sql);

                $data['lastid'] = $this->db->getLastId();

                if (null === $data['lastid']) {
                    throw new Exception('Could not insert into journal_request');
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

    public function following($params)
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
                      join journal_shared js
                        on u.userid = js.userid
                     where u.active = 1
                       and js.my_userid = ?;';

            $result = $this->db->query([
                $params['userid'],
            ], $sql);

            foreach ($result as $res) {
                $count = $res->count;
            }

            if (null === $count) {
                throw new Exception('Count null');
            }

            if ($count > 0) {
                $data['following'] = $count;

                $sql = 'select u.userid, u.firstname, u.surname, js.datedon
                          from users u
                          join journal_shared js
                            on u.userid = js.userid
                         where u.active = 1
                           and js.my_userid = ?
                         order by u.firstname, u.surname;';

                $result = $this->db->query([
                    $params['userid'],
                ], $sql);

                foreach ($result as $key => $res) {
                    $data[$key] = [$this->encrypt->scramble($res->userid, 'userid', $params['userid']), $res->firstname, $res->surname, $res->datedon];

                    if (null === $data[$key][0]) {
                        throw new Exception('Encryption failed for userid');
                    }
                }
            } else {
                $data['following'] = null;
                $data['count'] = 0;
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

    public function unfollow($params)
    {
        
        $data = null;

        $removeid = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            if (isset($params['formArray'])) {
                foreach ($params['formArray'] as $param) {
                    if (strlen($param) > 1) {
                        $removeid = $this->encrypt->unscramble($param, 'userid');

                        if (null === $removeid) {
                            throw new Exception('Decryption failed for user removeid');
                        }
                    }
                }
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params),
                'removeid' => $removeid,
            ]);

            if (null !== $removeid) {
                $sql = 'delete from journal_shared where my_userid = ? and userid = ?';

                $result = $this->db->query([
                    $params['userid'],
                    $removeid,
                ], $sql);

                $data['lastid'] = $this->db->getLastId();

                if (null === $data['lastid']) {
                    throw new Exception('Data[lastid] returned null');
                }

                $sql = 'select firstname, surname from users where userid = ?';

                $result = $this->db->query([
                    $removeid,
                ], $sql);

                foreach ($result as $res) {
                    $data['user'] = [$res->firstname, $res->surname];
                }

                if (null === $data['user']) {
                    throw new Exception('Data[user] returned null');
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

    public function followed($params)
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
                      join journal_shared js
                        on u.userid = js.my_userid
                     where js.userid = ?;';

            $result = $this->db->query([
                $params['userid'],
            ], $sql);

            foreach ($result as $res) {
                $count = $res->count;
            }

            if (null === $count) {
                throw new Exception('Count returned null');
            }

            if ($count > 0) {
                $data['followed'] = $count;

                $sql = 'select u.userid, u.firstname, u.surname, js.datedon
                          from users u
                          join journal_shared js
                            on u.userid = js.my_userid
                         where js.userid = ?
                         order by u.firstname, u.surname;';

                $result = $this->db->query([
                    $params['userid'],
                ], $sql);

                foreach ($result as $key => $res) {
                    $data[$key] = [$this->encrypt->scramble($res->userid, 'userid', $params['userid']), $res->firstname, $res->surname, $res->datedon];

                    if (null === $data[$key][0]) {
                        throw new Exception('Encryption failed for userid');
                    }
                }
            } else {
                $data['followed'] = null;
                $data['count'] = 0;
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

    public function removeFollow($params)
    {
        
        $data = null;

        $removeid = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            if (isset($params['formArray'])) {
                foreach ($params['formArray'] as $param) {
                    if (strlen($param) > 1) {
                        $removeid = $this->encrypt->unscramble($param, 'userid');

                        if (null === $removeid) {
                            throw new Exception('Decryption failed for user removeid');
                        }
                    }
                }
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params),
                'removeid' => $removeid,
            ]);

            if (null !== $removeid) {
                $sql = 'delete from journal_shared where my_userid = ? and userid = ?';

                $result = $this->db->query([
                    $removeid,
                    $params['userid'],
                ], $sql);

                $data['lastid'] = $this->db->getLastId();

                if (null === $data['lastid']) {
                    throw new Exception('Data[lastid] returned null');
                }

                $sql = 'select firstname, surname from users where userid = ?';

                $result = $this->db->query([
                    $removeid,
                ], $sql);

                foreach ($result as $res) {
                    $data['user'] = [$res->firstname, $res->surname];
                }

                if (null === $data['user']) {
                    throw new Exception('Data[user] returned null');
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

    public function callsLoadShareable($params)
    {
        
        $calls = null;

        try {
            $calls['store'] = $this->encrypt->scramble('store', 'method', $params['userid']);

            $calls['ConfigSharing1'] = $this->encrypt->scramble('Journal\ConfigSharing\ConfigSharing', 'controller', $params['userid']);

            $calls['shared'] = $this->encrypt->scramble('shared', 'method', $params['userid']);

            $calls['ConfigSharing2'] = $this->encrypt->scramble('Journal\ConfigSharing\ConfigSharing', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['store']:
                    throw new Exception('Encryption failed of store');

                    break;

                case null === $calls['ConfigSharing1']:
                    throw new Exception('Encryption failed of ConfigSharing1');

                    break;

                case null === $calls['shared']:
                    throw new Exception('Encryption failed of shared');

                    break;

                case null === $calls['ConfigSharing2']:
                    throw new Exception('Encryption failed of ConfigSharing2');

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

    public function callsShared($params)
    {
        
        $calls = null;

        try {
            $calls['request'] = $this->encrypt->scramble('request', 'method', $params['userid']);

            $calls['ConfigSharing1'] = $this->encrypt->scramble('Journal\ConfigSharing\ConfigSharing', 'controller', $params['userid']);

            $calls['following'] = $this->encrypt->scramble('following', 'method', $params['userid']);

            $calls['ConfigSharing2'] = $this->encrypt->scramble('Journal\ConfigSharing\ConfigSharing', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['request']:
                    throw new Exception('Encryption failed for request');

                    break;

                case null === $calls['ConfigSharing1']:
                    throw new Exception('Encryption failed for ConfigSharing1');

                    break;

                case null === $calls['following']:
                    throw new Exception('Encryption failed for following');

                    break;

                case null === $calls['ConfigSharing2']:
                    throw new Exception('Encryption failed for ConfigSharing2');

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

    public function callsStore($params)
    {
        
        $calls = null;

        try {
            $calls['loadShareable'] = $this->encrypt->scramble('loadShareable', 'method', $params['userid']);

            $calls['ConfigSharing'] = $this->encrypt->scramble('Journal\ConfigSharing\ConfigSharing', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['loadShareable']:
                    throw new Exception('Encryption failed for loadShareable');

                    break;

                case null === $calls['ConfigSharing']:
                    throw new Exception('Encryption failed for ConfigSharing');

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

    public function callsRequest($params)
    {
        
        $calls = null;

        try {
            $calls['loadShareable'] = $this->encrypt->scramble('loadShareable', 'method', $params['userid']);

            $calls['ConfigSharing'] = $this->encrypt->scramble('Journal\ConfigSharing\ConfigSharing', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['loadShareable']:
                    throw new Exception('Encryption failed for loadShareable');

                    break;

                case null === $calls['ConfigSharing']:
                    throw new Exception('Encryption failed for ConfigSharing');

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

    public function callsFollowing($params)
    {
        
        $calls = null;

        try {
            $calls['unfollow'] = $this->encrypt->scramble('unfollow', 'method', $params['userid']);

            $calls['ConfigSharing1'] = $this->encrypt->scramble('Journal\ConfigSharing\ConfigSharing', 'controller', $params['userid']);

            $calls['followed'] = $this->encrypt->scramble('followed', 'method', $params['userid']);

            $calls['ConfigSharing2'] = $this->encrypt->scramble('Journal\ConfigSharing\ConfigSharing', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['unfollow']:
                    throw new Exception('Encryption failed for unfollow');

                    break;

                case null === $calls['ConfigSharing1']:
                    throw new Exception('Encryption failed for ConfigSharing1');

                    break;

                case null === $calls['followed']:
                    throw new Exception('Encryption failed for followed');

                    break;

                case null === $calls['ConfigSharing2']:
                    throw new Exception('Encryption failed for ConfigSharing2');

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

    public function callsUnfollow($params)
    {
        
        $calls = null;

        try {
            $calls['loadShareable'] = $this->encrypt->scramble('loadShareable', 'method', $params['userid']);

            $calls['ConfigSharing'] = $this->encrypt->scramble('Journal\ConfigSharing\ConfigSharing', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['loadShareable']:
                    throw new Exception('Encryption failed for loadShareable');

                    break;

                case null === $calls['ConfigSharing']:
                    throw new Exception('Encryption failed for ConfigSharing');

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

    public function callsFollowed($params)
    {
        
        $calls = null;

        try {
            $calls['removeFollow'] = $this->encrypt->scramble('removeFollow', 'method', $params['userid']);

            $calls['ConfigSharing'] = $this->encrypt->scramble('Journal\ConfigSharing\ConfigSharing', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['removeFollow']:
                    throw new Exception('Encryption failed for removeFollow');

                    break;

                case null === $calls['ConfigSharing']:
                    throw new Exception('Encryption failed for ConfigSharing');

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

    public function callsRemoveFollow($params)
    {
        
        $calls = null;

        try {
            $calls['loadShareable'] = $this->encrypt->scramble('loadShareable', 'method', $params['userid']);

            $calls['ConfigSharing'] = $this->encrypt->scramble('Journal\ConfigSharing\ConfigSharing', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['loadShareable']:
                    throw new Exception('Encryption failed for loadShareable');

                    break;

                case null === $calls['ConfigSharing']:
                    throw new Exception('Encryption failed for ConfigSharing');

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