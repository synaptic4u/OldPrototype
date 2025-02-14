<?php

namespace Synaptic4U\Packages\Hierachy\Applications;

use Exception;
use Synaptic4U\Core\DB;
use Synaptic4U\Core\Log;
use Synaptic4U\Core\Validate;
use Synaptic4U\Core\Encryption;

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

            $path = dirname(__FILE__, 1) . '/Models/';

            $this->validate = new Validate($path);

            // $this->log([
            //     'Location' => __METHOD__ . '()',
            //     'Dir' => dirname(__FILE__, 1),
            //     'path' => $path,
            //     'validate' => serialize($this->validate),
            // ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__ . '()',
                '$e' => $e->__toString(),
            ]);

            return null;
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
                $hierachyid = $this->encrypt->unscramble($params['formArray'][0], 'hierachyid');
                $detid = $this->encrypt->unscramble($params['formArray'][1], 'detid');

                if (null === $hierachyid) {
                    throw new Exception('Unable to decrypt HierachyId');
                }
                if (null === $detid) {
                    throw new Exception('Unable to decrypt detid');
                }
            } else {
                throw new Exception("Form params do not meet the required length");
            }

            $data['hierachyid'] =  [
                'pass' => 0,
                'message' => null,
                'value' => $hierachyid,
            ];
            $data['detid'] =  [
                'pass' => 0,
                'message' => null,
                'value' => $detid,
            ];

            // $this->log([
            //     'Location' => __METHOD__ . '()',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'data' => json_encode($data, JSON_PRETTY_PRINT),
            // ]);

            $sql = 'select count(*) as count from hierachy_applications where hierachyid = ?';

            $result = $this->db->query([
                $data['hierachyid']['value']
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['count'] = $res->count;
            }

            if ((int)$data['count'] > 0) {
                $sql = 'select ha.appid, ha.updatedon, a.name
                          from hierachy_applications ha
                          join applications a 
                            on ha.appid = a.appid
                         where ha.hierachyid = ? 
                         order by a.name;';

                $result = $this->db->query([
                    $data['hierachyid']['value']
                ], $sql, __METHOD__);

                foreach ($result as $res) {
                    $data['subscribedApps'][$res->appid] = [
                        'id' => $res->appid,
                        'appid' => [
                            'pass' => 0,
                            'message' => null,
                            'value' => $this->encrypt->scramble($res->appid, 'appid', $params['userid']),
                        ],
                        'name' => [
                            'pass' => 0,
                            'message' => null,
                            'value' => $res->name,
                        ],
                        'addedon' => [
                            'pass' => 0,
                            'message' => null,
                            'value' => $res->updatedon,
                        ],
                    ];

                    if ($data['subscribedApps'][$res->appid]['appid'] === null) {
                        throw new Exception("Could not encrypt " . $res->name);
                    }
                }
            }

            $data['hierachyid']['value'] =  $this->encrypt->scramble($data['hierachyid']['value'], 'hierachyid', $params['userid']);
            $data['detid']['value'] =  $this->encrypt->scramble($data['detid']['value'], 'detid', $params['userid']);

            if (null === $data['hierachyid']['value']) {
                throw new Exception('Unable to decrypt hierachyId');
            }
            if (null === $data['detid']['value']) {
                throw new Exception('Unable to decrypt detid');
            }

            $this->log([
                'Location' => __METHOD__ . '()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);

            if (null === $data) {
                throw new Exception('Unable to query applications');
            }
        } catch (Exception $e) {
            $data = null;

            $this->error([
                'Location' => __METHOD__ . '()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    public function listApps($params, $ids = '0', $id = null)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        try {
            $data = [];

            $sql = 'select count(*) as count 
                      from applications 
                     where appid not in(?);';

            $result = $this->db->query([
                $ids
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['availablecount'] = $res->count;
            }

            if ((int)$data['availablecount'] > 0) {
                $sql = 'select appid, name as app, description 
                        from applications 
                        where appid not in(?) 
                        order by name;';

                $result = $this->db->query([
                    $ids
                ], $sql, __METHOD__);

                $data['headings'] = [
                    'name' => 'appid',
                    'id' => 'SelectApplications',
                    'legend' => 'Available Applications',
                    'required' => 'required',
                    'invalid_msg' => 'A selection is required.'
                ];

                foreach ($result as $res) {
                    $data['select'][] = [
                        'appid' => $this->encrypt->scramble($res->appid, 'appid', $params['userid']),
                        'id' => $res->appid,
                        'name' => [
                            'pass' => (isset($id) && (int)$id['value'] === (int)$res->appid) ? $id['pass'] : 0,
                            'message' => (isset($id) && (int)$id['value'] === (int)$res->appid) ? $id['message'] : null,
                            'value' => $res->app,
                            'selected' => (isset($id) && (int)$id['value'] === (int)$res->appid) ? 1 : 0,
                        ],
                        'description' => [
                            'pass' => 0,
                            'message' => null,
                            'value' => $res->description,
                        ],
                    ];
                }
            }

            $this->log([
                'Location' => __METHOD__ . '()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);

            if (null === $data) {
                throw new Exception('Unable to query applications');
            }
        } catch (Exception $e) {
            $data = null;

            $this->error([
                'Location' => __METHOD__ . '()',
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

        // $this->log([
        //     'Location' => __METHOD__ . '() : 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        try {
            if (sizeof($params['formArray']) > 1) {
                $params['formArray']['hierachyid'] = $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
                $params['formArray']['detid'] = $this->encrypt->unscramble($params['formArray']['detid'], 'detid');
                $params['formArray']['appid'] = $this->encrypt->unscramble($params['formArray']['appid'], 'appid');

                if (null === $params['formArray']['hierachyid']) {
                    throw new Exception('Unable to decrypt hierachyId');
                }
                if (null === $params['formArray']['detid']) {
                    throw new Exception('Unable to decrypt detid');
                }
                if (null === $params['formArray']['appid']) {
                    throw new Exception('Unable to decrypt appid');
                }
            } else {
                throw new Exception("Form params do not meet the required length");
            }

            $data = $this->validate->isValid('Applications', $params['formArray']);

            // $this->log([
            //     'Location' => __METHOD__ . '() : 2',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'data' => json_encode($data, JSON_PRETTY_PRINT),
            // ]);

            if (!is_null($data)) {
                // $this->log([
                //     'Location' => __METHOD__ . '(): 3',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                // ]);

                if ($data['return'] > 0) {
                    return $data;
                }

                $sql = 'select name 
                          from applications 
                         where appid = ?;';

                $result = $this->db->query([
                    $data['appid']['value']
                ], $sql, __METHOD__);

                foreach ($result as $res) {
                    $data['appname'] = $res->name;
                }

                $sql = 'insert into hierachy_applications(hierachyid, appid)
                            values(?, ?);';

                $result = $this->db->query([
                    $data['hierachyid']['value'],
                    $data['appid']['value'],
                ], $sql, __METHOD__);

                $data['rowcount'] = $this->db->getrowCount();
                $data['appid']['id'] = $data['appid']['value'];

                $roleid = null;

                $sql = 'select roleid from hierachy_users where userid = ? and hierachyid = ?;';

                $result = $this->db->query([
                    $params['userid'],
                    $data['hierachyid']['value'],
                ], $sql, __METHOD__);

                foreach ($result as $res) {
                    $roleid = $res->roleid;
                }

                $sql = 'insert into hierachy_app_users(hierachyid, appid, roleid, userid)values(?, ?, ?, ?);';

                $result = $this->db->query([
                    $data['hierachyid']['value'],
                    $data['appid']['value'],
                    $roleid,
                    $params['userid'],
                ], $sql, __METHOD__);

                $data['hierachyid']['value'] = $this->encrypt->scramble($data['hierachyid']['value'], 'hierachyid', $params['userid']);
                $data['detid']['value'] = $this->encrypt->scramble($data['detid']['value'], 'detid', $params['userid']);
                $data['appid']['value'] = $this->encrypt->scramble($data['appid']['value'], 'appid', $params['userid']);

                if ($data['hierachyid']['value'] === null) {
                    throw new Exception("Unable to encrypt hierachyid.");
                }
                if ($data['detid']['value'] === null) {
                    throw new Exception("Unable to encrypt detid.");
                }
                if ($data['appid']['value'] === null) {
                    throw new Exception("Unable to encrypt appid.");
                }

                $this->log([
                    'Location' => __METHOD__ . '(): 4',
                    'params' => json_encode($params, JSON_PRETTY_PRINT),
                    'data' => json_encode($data, JSON_PRETTY_PRINT),
                ]);
            }

            if (null === $data) {
                throw new Exception('Could not add Applications');
            }
        } catch (Exception $e) {
            $data = null;

            $this->error([
                'Location' => __METHOD__ . '()',
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

        // $this->log([
        //     'Location' => __METHOD__ . '() : 1',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        try {
            if (sizeof($params['formArray']) > 1) {
                $hierachyid = $this->encrypt->unscramble($params['formArray'][0], 'hierachyid');
                $detid = $this->encrypt->unscramble($params['formArray'][1], 'detid');
                $appid = $this->encrypt->unscramble($params['formArray'][2], 'appid');

                if (null === $hierachyid) {
                    throw new Exception('Unable to decrypt hierachyId');
                }
                if (null === $detid) {
                    throw new Exception('Unable to decrypt detid');
                }
                if (null === $appid) {
                    throw new Exception('Unable to decrypt appid');
                }
            } else {
                throw new Exception("Form params do not meet the required length");
            }

            $data = $this->validate->isValid('Applications', [
                'hierachyid' => $hierachyid,
                'detid' => $detid,
                'appid' => $appid
            ]);

            // $this->log([
            //     'Location' => __METHOD__ . '() : 2',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'data' => json_encode($data, JSON_PRETTY_PRINT),
            // ]);

            if (!is_null($data)) {
                // $this->log([
                //     'Location' => __METHOD__ . '(): 3',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                // ]);

                if ($data['return'] > 0) {
                    return $data;
                }

                $sql = 'select name 
                          from applications 
                         where appid = ?;';

                $result = $this->db->query([
                    $data['appid']['value']
                ], $sql, __METHOD__);

                foreach ($result as $res) {
                    $data['appname'] = $res->name;
                }

                $sql = 'delete from hierachy_applications where hierachyid = ? and appid = ?;';

                $result = $this->db->query([
                    $data['hierachyid']['value'],
                    $data['appid']['value'],
                ], $sql, __METHOD__);

                $data['rowcount'] = $this->db->getrowCount();

                $data['hierachyid']['value'] = $this->encrypt->scramble($data['hierachyid']['value'], 'hierachyid', $params['userid']);
                $data['detid']['value'] = $this->encrypt->scramble($data['detid']['value'], 'detid', $params['userid']);

                if ($data['hierachyid']['value'] === null) {
                    throw new Exception("Unable to encrypt hierachyid.");
                }
                if ($data['detid']['value'] === null) {
                    throw new Exception("Unable to encrypt detid.");
                }

                $this->log([
                    'Location' => __METHOD__ . '(): 4',
                    'params' => json_encode($params, JSON_PRETTY_PRINT),
                    'data' => json_encode($data, JSON_PRETTY_PRINT),
                ]);
            }

            if (null === $data) {
                throw new Exception('Could not remove Applications');
            }
        } catch (Exception $e) {
            $data = null;

            $this->error([
                'Location' => __METHOD__ . '()',
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

            $calls['types'] = $this->encrypt->scramble('Hierachy\Types\Types', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);
            $calls['applications'] = $this->encrypt->scramble('Hierachy\Applications\Applications', 'controller', $params['userid']);
            $calls['store'] = $this->encrypt->scramble('store', 'method', $params['userid']);
            $calls['remove'] = $this->encrypt->scramble('remove', 'method', $params['userid']);

            switch (true) {
                case null === $calls['types']:
                    throw new Exception('Encryption failed of controller: types');

                    break;

                case null === $calls['show']:
                    throw new Exception('Encryption failed of method: show');

                    break;
                case null === $calls['applications']:
                    throw new Exception('Encryption failed of controller: applications');

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
                'Location' => __METHOD__ . '()',
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

            $calls['applications'] = $this->encrypt->scramble('Hierachy\Applications\Applications', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['applications']:
                    throw new Exception('Encryption failed of controller: applications');

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
                'Location' => __METHOD__ . '()',
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

            $calls['applications'] = $this->encrypt->scramble('Hierachy\Applications\Applications', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['applications']:
                    throw new Exception('Encryption failed of controller: applications');

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
                'Location' => __METHOD__ . '()',
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