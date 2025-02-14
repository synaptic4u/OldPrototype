<?php

namespace Synaptic4U\Packages\Hierachy\AppUsers;

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
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $this->encrypt = new Encryption();

            $this->db = new DB();

            $path = dirname(__FILE__, 1).'/Models/';

            $this->validate = new Validate($path);

            // $this->log([
            //     'Location' => __METHOD__ . '()',
            //     'Dir' => dirname(__FILE__, 1),
            //     'path' => $path,
            // ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            return null;
        }
    }

    public function getUsers($params, $userids = [])
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $params['formArray']['hierachyid'] = $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
                $params['formArray']['appid'] = $this->encrypt->unscramble($params['formArray']['appid'], 'appid');
                $params['formArray']['moduleid'] = $this->encrypt->unscramble($params['formArray']['moduleid'], 'moduleid');

                switch (true) {
                    case null === $params['formArray']['hierachyid']:
                        throw new Exception('Unable to decrypt : hierachyid');

                        break;

                    case null === $params['formArray']['appid']:
                        throw new Exception('Unable to decrypt : appid');

                        break;

                    case null === $params['formArray']['moduleid']:
                        throw new Exception('Unable to decrypt : moduleid');

                        break;
                }
            } else {
                throw new Exception('Form params do not meet the required length');
            }

            // $this->log([
            //     'Location' => __METHOD__ . '(): 0',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'userids' => json_encode($userids, JSON_PRETTY_PRINT),
            // ]);

            $sql = 'select count(*) as count 
                      from hierachy_users 
                     where hierachyid = ?
                       and active = 1
                       and personnel = 0;';

            $result = $this->db->query([
                $params['formArray']['hierachyid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['count'] = $res->count;
            }

            if ((int) $data['count'] > 0) {
                $sql = 'select hu.hierachyid, hu.userid, 
                               concat(u.firstname, " ", u.surname) as user
                          from hierachy_users hu
                          join users u
                            on hu.userid = u.userid
                         where hu.hierachyid = ?
                           and hu.active = 1
                           and hu.personnel = 0
                         order by u.firstname, u.surname ASC;';

                $result = $this->db->query([
                    $params['formArray']['hierachyid'],
                ], $sql, __METHOD__);

                foreach ($result as $res) {
                    if (!in_array($res->userid, $userids) || 0 == sizeof($userids)) {
                        $data['users'][$res->userid] = [
                            'hierachyid' => $res->hierachyid,
                            'userid' => [
                                'pass' => 0,
                                'message' => null,
                                'value' => $this->encrypt->scramble($res->userid, 'userid', $params['userid']),
                            ],
                            'user' => [
                                'pass' => 0,
                                'message' => null,
                                'value' => $res->user,
                            ],
                        ];

                        if (null === $data['users'][$res->userid]['userid']['value']) {
                            throw new Exception('Could not encrypt userid: '.$res->userid);
                        }
                    }
                }
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'userids' => json_encode($userids, JSON_PRETTY_PRINT),
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);

            if (null === $data) {
                throw new Exception('Unable to query applications');
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

    public function show($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $params['formArray']['hierachyid'] = $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
                $params['formArray']['appid'] = $this->encrypt->unscramble($params['formArray']['appid'], 'appid');
                $params['formArray']['moduleid'] = $this->encrypt->unscramble($params['formArray']['moduleid'], 'moduleid');

                switch (true) {
                    case null === $params['formArray']['hierachyid']:
                        throw new Exception('Unable to decrypt : hierachyid');

                        break;

                    case null === $params['formArray']['appid']:
                        throw new Exception('Unable to decrypt : appid');

                        break;

                    case null === $params['formArray']['moduleid']:
                        throw new Exception('Unable to decrypt : moduleid');

                        break;
                }
            } else {
                throw new Exception('Form params do not meet the required length');
            }

            // $this->log([
            //     'Location' => __METHOD__ . '(): 0',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $sql = 'select count(*) as count 
                      from hierachy_module_users 
                     where hierachyid = ? 
                       and moduleid = ?;';

            $result = $this->db->query([
                $params['formArray']['hierachyid'],
                $params['formArray']['moduleid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['count'] = $res->count;
            }

            $data['users'] = [];

            if ((int) $data['count'] > 0) {
                $sql = 'select hmu.moduleid, hmu.userid, 
                               concat(u.firstname, " ", u.surname) as user, 
                               concat(uu.firstname, " ", uu.surname) as maintainedby, 
                               hmu.updatedon, hmu.include_exclude
                          from hierachy_module_users hmu
                          join users u
                            on hmu.userid = u.userid
                          join users uu
                            on hmu.maintainedby = uu.userid
                         where hmu.hierachyid = ?
                           and hmu.moduleid = ?
                         order by hmu.include_exclude, u.firstname, u.surname ASC;';

                $result = $this->db->query([
                    $params['formArray']['hierachyid'],
                    $params['formArray']['moduleid'],
                ], $sql, __METHOD__);

                foreach ($result as $res) {
                    $data['users'][$res->userid] = [
                        'modid' => $res->moduleid,
                        'userid' => [
                            'pass' => 0,
                            'message' => null,
                            'value' => $this->encrypt->scramble($res->userid, 'userid', $params['userid']),
                        ],
                        'user' => [
                            'pass' => 0,
                            'message' => null,
                            'value' => $res->user,
                        ],
                        'maintainedby' => [
                            'pass' => 0,
                            'message' => null,
                            'value' => $res->maintainedby,
                        ],
                        'updatedon' => [
                            'pass' => 0,
                            'message' => null,
                            'value' => $res->updatedon,
                        ],
                        'include_exclude' => [
                            'pass' => 0,
                            'message' => null,
                            'value' => $res->include_exclude,
                        ],
                    ];

                    if (null === $data['users'][$res->userid]['userid']['value']) {
                        throw new Exception('Could not encrypt userid: '.$res->userid);
                    }
                }
            }

            $data['moduleid']['id'] = $params['formArray']['moduleid'];

            $data['hierachyid']['value'] = $this->encrypt->scramble($params['formArray']['hierachyid'], 'hierachyid', $params['userid']);
            $data['appid']['value'] = $this->encrypt->scramble($params['formArray']['appid'], 'appid', $params['userid']);
            $data['moduleid']['value'] = $this->encrypt->scramble($params['formArray']['moduleid'], 'moduleid', $params['userid']);

            switch (true) {
                case null === $data['hierachyid']['value']:
                    throw new Exception('Encryption failed for: hierachyid');

                    break;

                case null === $data['appid']['value']:
                    throw new Exception('Encryption failed for: appid');

                    break;

                case null === $data['moduleid']['value']:
                    throw new Exception('Encryption failed for: moduleid');

                    break;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);

            if (null === $data) {
                throw new Exception('Unable to query applications');
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
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $params['formArray']['hierachyid'] = $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
                $params['formArray']['appid'] = $this->encrypt->unscramble($params['formArray']['appid'], 'appid');
                $params['formArray']['moduleid'] = $this->encrypt->unscramble($params['formArray']['moduleid'], 'moduleid');
                $params['formArray']['userid'] = $this->encrypt->unscramble($params['formArray']['userid'], 'userid');

                switch (true) {
                    case null === $params['formArray']['hierachyid']:
                        throw new Exception('Unable to decrypt : hierachyid');

                        break;

                    case null === $params['formArray']['appid']:
                        throw new Exception('Unable to decrypt : appid');

                        break;

                    case null === $params['formArray']['moduleid']:
                        throw new Exception('Unable to decrypt : moduleid');

                        break;

                    case null === $params['formArray']['moduleid']:
                        throw new Exception('Unable to decrypt : moduleid');

                        break;
                }
            } else {
                throw new Exception('Form params do not meet the required length');
            }

            $params['formArray']['include_exclude'] = ('off' === (string) $params['formArray']['include_exclude']) ? 0 : 1;

            $data = $this->validate->isValid('AppUser', $params['formArray']);
            $data['moduleid']['id'] = $data['moduleid']['value'];
            $data['userid']['id'] = $data['userid']['value'];

            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'data' => json_encode($data, JSON_PRETTY_PRINT),
            // ]);

            if ($data['return'] > 0) {
                return $data;
            }

            $sql = 'select count(*) as count 
                      from hierachy_module_users 
                     where hierachyid = ?
                       and moduleid = ?
                       and userid = ?;';

            $result = $this->db->query([
                $data['hierachyid']['value'],
                $data['moduleid']['value'],
                $data['userid']['value'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['count'] = $res->count;
            }

            if (0 === (int) $data['count']) {
                $sql = 'insert into hierachy_module_users(hierachyid, userid, moduleid, maintainedby, include_exclude)
                        values(?, ?, ?, ?, ?);';

                $result = $this->db->query([
                    $data['hierachyid']['value'],
                    $data['userid']['value'],
                    $data['moduleid']['value'],
                    $params['userid'],
                    $data['include_exclude']['value'],
                ], $sql, __METHOD__);

                $data['rowcount'] = $this->db->getrowCount();
            }

            $data['hierachyid']['value'] = $this->encrypt->scramble($data['hierachyid']['value'], 'hierachyid', $params['userid']);
            $data['appid']['value'] = $this->encrypt->scramble($data['appid']['value'], 'appid', $params['userid']);
            $data['moduleid']['value'] = $this->encrypt->scramble($data['moduleid']['value'], 'moduleid', $params['userid']);
            $data['userid']['value'] = $this->encrypt->scramble($data['userid']['value'], 'userid', $params['userid']);

            switch (true) {
                case null === $data['hierachyid']['value']:
                    throw new Exception('Encryption failed for: hierachyid');

                    break;

                case null === $data['appid']['value']:
                    throw new Exception('Encryption failed for: appid');

                    break;

                case null === $data['moduleid']['value']:
                    throw new Exception('Encryption failed for: moduleid');

                    break;

                case null === $data['userid']['value']:
                    throw new Exception('Encryption failed for: userid');

                    break;
            }

            $this->log([
                'Location' => __METHOD__.'(): 4',
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

    public function remove($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $params['formArray']['hierachyid'] = $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
                $params['formArray']['appid'] = $this->encrypt->unscramble($params['formArray']['appid'], 'appid');
                $params['formArray']['moduleid'] = $this->encrypt->unscramble($params['formArray']['moduleid'], 'moduleid');
                $params['formArray']['userid'] = $this->encrypt->unscramble($params['formArray']['userid'], 'userid');

                switch (true) {
                    case null === $params['formArray']['hierachyid']:
                        throw new Exception('Unable to decrypt : hierachyid');

                        break;

                    case null === $params['formArray']['appid']:
                        throw new Exception('Unable to decrypt : appid');

                        break;

                    case null === $params['formArray']['moduleid']:
                        throw new Exception('Unable to decrypt : moduleid');

                        break;

                    case null === $params['formArray']['moduleid']:
                        throw new Exception('Unable to decrypt : moduleid');

                        break;
                }
            } else {
                throw new Exception('Form params do not meet the required length');
            }

            $data['moduleid']['id'] = $params['formArray']['moduleid'];
            $data['userid']['id'] = $params['formArray']['userid'];

            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'data' => json_encode($data, JSON_PRETTY_PRINT),
            // ]);

            $sql = 'select count(*) as count 
                      from hierachy_module_users 
                     where hierachyid = ?
                       and moduleid = ?
                       and userid = ?;';

            $result = $this->db->query([
                $params['formArray']['hierachyid'],
                $params['formArray']['moduleid'],
                $params['formArray']['userid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['count'] = $res->count;
            }

            if (1 === (int) $data['count']) {
                $sql = 'delete 
                          from hierachy_module_users
                         where hierachyid = ? 
                           and userid = ?
                           and moduleid = ?;';

                $result = $this->db->query([
                    $params['formArray']['hierachyid'],
                    $params['formArray']['userid'],
                    $params['formArray']['moduleid'],
                ], $sql, __METHOD__);

                $data['rowcount'] = $this->db->getrowCount();
            }

            $data['hierachyid']['value'] = $this->encrypt->scramble($params['formArray']['hierachyid'], 'hierachyid', $params['userid']);
            $data['appid']['value'] = $this->encrypt->scramble($params['formArray']['appid'], 'appid', $params['userid']);
            $data['moduleid']['value'] = $this->encrypt->scramble($params['formArray']['moduleid'], 'moduleid', $params['userid']);

            switch (true) {
                case null === $data['hierachyid']['value']:
                    throw new Exception('Encryption failed for: hierachyid');

                    break;

                case null === $data['appid']['value']:
                    throw new Exception('Encryption failed for: appid');

                    break;

                case null === $data['moduleid']['value']:
                    throw new Exception('Encryption failed for: moduleid');

                    break;
            }

            $this->log([
                'Location' => __METHOD__.'(): 4',
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

    public function callsShow($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['appusers'] = $this->encrypt->scramble('Hierachy\AppUsers\AppUsers', 'controller', $params['userid']);
            $calls['store'] = $this->encrypt->scramble('store', 'method', $params['userid']);
            $calls['remove'] = $this->encrypt->scramble('remove', 'method', $params['userid']);

            switch (true) {
                case null === $calls['appusers']:
                    throw new Exception('Encryption failed of controller: appusers');

                    break;

                case null === $calls['store']:
                    throw new Exception('Encryption failed of method: store');

                    break;

                case null === $calls['remove']:
                    throw new Exception('Encryption failed of method: remove');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__ . '()',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
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

    public function callsCreated($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['appusers'] = $this->encrypt->scramble('Hierachy\AppUsers\AppUsers', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['appusers']:
                    throw new Exception('Encryption failed of controller: appusers');

                    break;

                case null === $calls['show']:
                    throw new Exception('Encryption failed of method: show');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__ . '()',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
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

    public function callsRemoved($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['appusers'] = $this->encrypt->scramble('Hierachy\AppUsers\AppUsers', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['appusers']:
                    throw new Exception('Encryption failed of controller: appusers');

                    break;

                case null === $calls['show']:
                    throw new Exception('Encryption failed of method: show');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__ . '()',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
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
