<?php

namespace Synaptic4U\Packages\Hierachy\Hierachy;

use Error;
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

            $path = dirname(__FILE__, 1) . '/Models/';

            $this->validate = new Validate($path);

            // $this->log([
            //     'Location' => __METHOD__ . '()',
            //     'Dir' => dirname(__FILE__, 1),
            //     'path' => $path,
            // ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__ . '()',
                '$e' => $e->__toString(),
            ]);
        }
    }

    public function getHierachyName($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $data = null;

        try {
            // $this->log([
            //     'Location' => __METHOD__.'(): 0',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT)
            // ]);

            if (sizeof($params['formArray']) > 1) {
                $hierachyid = (isset($params['formArray']['hierachyid'])) ? $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid') : $this->encrypt->unscramble($params['formArray'][0], 'hierachyid');
                
                if (null === $hierachyid) {
                    throw new Exception('Unable to decrypt HierachyId');
                }
            } else {
                throw new Exception("Form params do not meet the required length");
            }

            $sql = 'select name from hierachy_det where hierachyid = ?;';

            $result = $this->db->query([
                $hierachyid,
            ], $sql, __METHOD__);

            foreach($result as $res){
                $data = $res->name;
            }

            $this->log([
                'Location' => __METHOD__.'(): 1',
                'data' => json_encode($data, JSON_PRETTY_PRINT),
                'params' => json_encode($params, JSON_PRETTY_PRINT)
            ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__ . '()',
                '$e' => $e->__toString(),
            ]);

            $data = null;
        }

        return $data;
    }

    public function store($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);       
        $data = null;

        try {
            // $this->log([
            //     'Location' => __METHOD__ . '(): 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $params['formArray']['hierachyid'] = (int) $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
            $params['formArray']['hierachysubid'] = (int) $this->encrypt->unscramble($params['formArray']['hierachysubid'], 'hierachysubid');
            $params['formArray']['nested'] = (int) $this->encrypt->unscramble($params['formArray']['nested'], 'param');
            $params['formArray']['hierachytypeid'] = (int) $this->encrypt->unscramble($params['formArray']['hierachytypeid'], 'hierachytypeid');

            // $this->log([
            //     'Location' => __METHOD__ . '(): 2',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            if (null === $params['formArray']['hierachyid']) {
                throw new Exception('Unable to decrypt HierachyId');
            }
            if (null === $params['formArray']['hierachysubid']) {
                throw new Exception('Unable to decrypt HierachySubId');
            }
            if (null === $params['formArray']['nested']) {
                throw new Exception('Unable to decrypt Nested');
            }
            if (null === $params['formArray']['hierachytypeid']) {
                throw new Exception('Unable to decrypt Hierachytypeid');
            }

            $data = $this->validate->isValid('Hierachy_Det', $params['formArray']);
            // $data['hierachytypeid'] = $params['formArray']['hierachytypeid'];

            if (!is_null($data)) {
                // $this->log([
                //     'Location' => __METHOD__ . '(): 3',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                // ]);

                if ($data['return'] > 0) {
                    return $data;
                }

                $data['detid'] = 0;

                if (isset($params['formArray']['nested']) && 1 === (int)$params['formArray']['nested']) {
                    // $this->log([
                    //     'Location' => __METHOD__ . '(): 4',
                    //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                    //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                    //     'nested' => isset($params['formArray']['nested']),
                    //     'nested2' => (1 === (int)$params['formArray']['nested']),
                    // ]);
                    $levelid = null;

                    $sql = 'select levelid from hierachy where hierachyid = ? and hierachysubid = ?;';

                    $result = $this->db->query([
                        $data['hierachyid']['value'],
                        $data['hierachysubid']['value'],
                    ], $sql, __METHOD__);

                    foreach ($result as $res) {
                        $levelid = $res->levelid;
                    }
                    $levelid = (0 === (int) $levelid) ? 1 : $levelid;

                    $sql = 'update hierachy set levelid = ? where hierachyid = ?;';

                    $result = $this->db->query([
                        $levelid,
                        $data['hierachyid']['value'],
                    ], $sql, __METHOD__);
                }

                // $this->log([
                //     'Location' => __METHOD__ . '(): 5',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                //     'nested' => isset($params['formArray']['nested']),
                //     'nested2' => (1 === (int)$params['formArray']['nested']),
                // ]);

                $sql = 'insert into hierachy(hierachysubid, userid)
                            values(?, ?);';

                $result = $this->db->query([
                    (isset($params['formArray']['nested']) && 1 === (int)$params['formArray']['nested']) ? $data['hierachyid']['value'] : $data['hierachysubid']['value'],
                    $params['userid'],
                ], $sql, __METHOD__);

                $data['hierachyid']['value'] = $this->db->getLastId();

                if ((int) $data['hierachyid']['value'] > 0) {
                    $sql = 'insert into hierachy_det(hierachyid, userid, hierachytypeid, name, description)
                            values(?,?,?,?,?)';

                    $result = $this->db->query([
                        $data['hierachyid']['value'],
                        $params['userid'],
                        $data['hierachytypeid']['value'],
                        $data['hierachyname']['value'],
                        $data['hierachydescription']['value'],
                    ], $sql, __METHOD__);

                    $data['detid'] = $this->db->getLastId();

                    if ($data['detid'] < 1) {
                        throw new Exception('Could not insert Hierachy Det.');
                    }

                    $sql = 'insert into hierachy_users(hierachyid, userid)values(?,?);';

                    $result = $this->db->query([
                        $data['hierachyid']['value'],
                        $params['userid'],
                    ], $sql, __METHOD__);
                } else {
                    throw new Exception('Could not insert Hierachy.');
                }

                $this->log([
                    'Location' => __METHOD__ . '(): 4',
                    'hierachyid' => $data['hierachyid']['value'],
                    'params' => json_encode($params, JSON_PRETTY_PRINT),
                    'data' => json_encode($data, JSON_PRETTY_PRINT),
                ]);
            } else {
                throw new Exception('Could not validate Hierachy');
            }

            if (null === $data) {
                throw new Exception('Could not insert Hierachy');
            }
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__ . '()',
                '$e' => $e->__toString(),
            ]);
            $data = null;
        }

        return $data;
    }

    public function update($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);         
        $data = null;

        try {
            // $this->log([
            //     'Location' => __METHOD__ . '(): 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $params['formArray']['hierachyid'] = (int) $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
            $params['formArray']['hierachysubid'] = (int) $this->encrypt->unscramble($params['formArray']['hierachysubid'], 'hierachysubid');
            $params['formArray']['hierachydetid'] = (int) $this->encrypt->unscramble($params['formArray']['hierachydetid'], 'detid');
            $params['formArray']['hierachytypeid'] = (int) $this->encrypt->unscramble($params['formArray']['hierachytypeid'], 'hierachytypeid');
            $params['formArray']['hierachyvisibility'] = ((string)$params['formArray']['hierachyvisibility'] === "on") ? 1 : 0;

            // $this->log([
            //     'Location' => __METHOD__ . '(): 2',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            if (null === $params['formArray']['hierachyid']) {
                throw new Exception('Unable to decrypt HierachyId');
            }
            if (null === $params['formArray']['hierachysubid']) {
                throw new Exception('Unable to decrypt Hierachysubid');
            }
            if (null === $params['formArray']['hierachydetid']) {
                throw new Exception('Unable to decrypt Detid');
            }
            if (null === $params['formArray']['hierachytypeid']) {
                throw new Exception('Unable to decrypt Hierachytypeid');
            }

            $data = $this->validate->isValid('Hierachy_Det', $params['formArray']);

            if (!is_null($data)) {
                // $this->log([
                //     'Location' => __METHOD__ . '(): 3',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                // ]);

                if ($data['return'] > 0) {
                    return $data;
                }

                $data["hierachyvisibility"] = [
                    "value" => $params['formArray']['hierachyvisibility'],
                    "validate" => [
                        "value" => "pass"
                    ],
                    "pass" => 0,
                    "required" => "true",
                    "message" => "is-valid"
                ];
                $data["hierachydetid"] = [
                    "value" => $params['formArray']['hierachydetid'],
                    "validate" => [
                        "value" => "pass"
                    ],
                    "pass" => 0,
                    "required" => "true",
                    "message" => "is-valid"
                ];
                $data['count'] = 0;
                $data['countdet'] = 0;

                $sql = 'update hierachy
                           set visible = ?,
                               userid = ?
                         where hierachyid = ?;';

                $result = $this->db->query([
                    $data['hierachyvisibility']['value'],
                    $params['userid'],
                    $data['hierachyid']['value'],
                ], $sql, __METHOD__);

                $data['count'] = $this->db->getrowCount();

                $sql = 'update hierachy_det
                           set userid = ?, 
                               hierachytypeid = ?, 
                               name = ?, 
                               description = ?
                         where detid = ?';

                $result = $this->db->query([
                    $params['userid'],
                    $data['hierachytypeid']['value'],
                    $data['hierachyname']['value'],
                    $data['hierachydescription']['value'],
                    $data['hierachydetid']['value'],
                ], $sql, __METHOD__);

                $data['countdet'] = $this->db->getrowCount();

                $data['updated'] = [
                    'detid' => $this->encrypt->scramble($data['hierachydetid']['value'], 'detid', $params['userid']),
                    'hierachyid' => $this->encrypt->scramble($data['hierachyid']['value'], 'hierachyid', $params['userid']),
                ];

                $sql = 'select concat(firstname, " ", surname) as creator from users where userid = ?';


                $result = $this->db->query([
                    $params['userid'],
                ], $sql, __METHOD__);

                foreach ($result as $res) {
                    $data['hierachycreator'] = $res->creator;
                }

                $this->log([
                    'Location' => __METHOD__ . '(): 4',
                    'params' => json_encode($params, JSON_PRETTY_PRINT),
                    'data' => json_encode($data, JSON_PRETTY_PRINT),
                ]);
            } else {
                throw new Exception('Could not validate Hierachy');
            }

            if (null === $data) {
                throw new Exception('Could not update Hierachy');
            }
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__ . '()',
                '$e' => $e->__toString(),
            ]);
            $data = null;
        }

        return $data;
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

            $sql = 'select hd.detid, hd.hierachyid, ht.name as type, hd.name as org, hd.description, 
                           concat(u.firstname, " ", u.surname) as creator, 
                           h.visible,
                           case when isnull(hdd.name) then "No parent organization" else hdd.name end as parentorg
                      from hierachy_det hd
                      join hierachy h
                        on hd.hierachyid = h.hierachyid
                      join users u
                        on h.userid = u.userid
                      join hierachy_type ht
                        on hd.hierachytypeid = ht.hierachytypeid
                      left join hierachy_det hdd
                        on h.hierachysubid = hdd.hierachyid
                     where hd.hierachyid = ?
                       and hd.detid = ?
                       and hd.userid = ?';

            $result = $this->db->query([
                $hierachyid,
                $detid,
                $params['userid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data = [
                    'detid' => $this->encrypt->scramble($res->detid, 'detid', $params['userid']),
                    'hierachyid' => $this->encrypt->scramble($res->hierachyid, 'hierachyid', $params['userid']),
                    'org' => $res->org,
                    'parentorg' => $res->parentorg,
                    'description' => $res->description,
                    'creator' => $res->creator,
                    'visible' => $res->visible,
                    'type' => $res->type,
                ];

                if (null === $data['detid']) {
                    throw new Exception('Unable to encrypt detid.');
                }
                if (null === $data['hierachyid']) {
                    throw new Exception('Unable to encrypt hierachyid.');
                }
            }

            $this->log([
                'Location' => __METHOD__ . '()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__ . '()',
                '$e' => $e->__toString(),
            ]);

            $data = null;
        }

        return $data;
    }

    public function edit($params)
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
            $sql = 'select hd.detid, hd.hierachyid, hd.hierachytypeid, hd.name as org, hd.description, 
                           concat(u.firstname, " ", u.surname) as creator, 
                           h.visible, h.hierachysubid,
                           case when isnull(hdd.name) then "No parent organization" else hdd.name end as parentorg
                      from hierachy_det hd
                      join hierachy h
                        on hd.hierachyid = h.hierachyid
                      join users u
                        on h.userid = u.userid
                      left join hierachy_det hdd
                        on h.hierachysubid = hdd.hierachyid
                     where hd.hierachyid = ?
                       and hd.detid = ?
                       and hd.userid = ?';

            $result = $this->db->query([
                $hierachyid,
                $detid,
                $params['userid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data = [
                    'hierachydetid' => [
                        'value' => $this->encrypt->scramble($res->detid, 'detid', $params['userid']),
                        'pass' => 0,
                        'message' => null
                    ],
                    'hierachyid' => [
                        'value' => $this->encrypt->scramble($res->hierachyid, 'hierachyid', $params['userid']),
                        'pass' => 0,
                        'message' => null
                    ],
                    'hierachysubid' => [
                        'value' => $this->encrypt->scramble($res->hierachysubid, 'hierachysubid', $params['userid']),
                        'pass' => 0,
                        'message' => null
                    ],
                    'hierachyname' => [
                        'pass' => 0,
                        'value' => $res->org,
                        'message' => null
                    ],
                    'hierachydescription' => [
                        'pass' => 0,
                        'value' => $res->description,
                        'message' => null
                    ],
                    'hierachyvisibility' => [
                        'value' => $res->visible,
                        'pass' => 0,
                        'message' => null
                    ],
                    'id' => $res->hierachytypeid,
                    'parentorg' => $res->parentorg,
                    'creator' => $res->creator,
                ];

                if (null === $data['hierachydetid']['value']) {
                    throw new Exception('Unable to encrypt detid.');
                }
                if (null === $data['hierachyid']['value']) {
                    throw new Exception('Unable to encrypt hierachyid.');
                }
            }

            $this->log([
                'Location' => __METHOD__ . '()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__ . '()',
                '$e' => $e->__toString(),
            ]);

            $data = null;
        }

        return $data;
    }

    public function getHierachyType($params, $typeid = null)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        
        $data = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $hierachyid = $this->encrypt->unscramble($params['formArray'][0], 'hierachyid');

                if (null === $hierachyid) {
                    throw new Exception('Unable to decrypt hierachyId');
                }
            } else {
                throw new Exception("Form params do not meet the required length");
            }

            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'hierachyid' => $hierachyid,
            // ]);

            $sql = 'call GetHierachyTypes(?);';

            $result = $this->db->callProc([
                $hierachyid
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data[$res->type] = [
                    'pass' => null,
                    'message' => null,
                    'hierachytypeid' => $this->encrypt->scramble($res->hierachytypeid, 'hierachytypeid', $params['userid']),
                    'value' => $res->type,
                    'selected' => ((int)$res->hierachytypeid === (int)$typeid) ? 1 : 0,
                ];

                if (null === $data[$res->type]['hierachytypeid']) {
                    throw new Exception('Unable to encrypt ' . $res->type . ' hierachytypeid.');
                }
            }

            $this->log([
                'Location' => __METHOD__ . '() : 2',
                'typeid' => $typeid,
                'hierachyid' => $hierachyid,
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__ . '()',
                '$e' => $e->__toString(),
            ]);

            $data = null;
        }

        return $data;
    }

    public function callsUpdate($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);        
        try {
            $calls = [];

            $calls['hierachy'] = $this->encrypt->scramble('Hierachy\Base\Hierachy', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);
            $calls['subhierachy'] = $this->encrypt->scramble('Hierachy\Hierachy\Hierachy', 'controller', $params['userid']);
            $calls['subshow'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['subhierachy']:
                    throw new Exception('Encryption failed of method: subhierachy');

                    break;

                case null === $calls['subshow']:
                    throw new Exception('Encryption failed of method: subshow');

                    break;
                case null === $calls['hierachy']:
                    throw new Exception('Encryption failed of controller: Hierachy');

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

    public function callsShow($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);    
        try {
            $calls = [];

            $calls['hierachy'] = $this->encrypt->scramble('Hierachy\Hierachy\Hierachy', 'controller', $params['userid']);
            $calls['edit'] = $this->encrypt->scramble('edit', 'method', $params['userid']);
            $calls['particulars'] = $this->encrypt->scramble('Hierachy\Particulars\Particulars', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['hierachy']:
                    throw new Exception('Encryption failed of controller: Hierachy');

                    break;

                case null === $calls['edit']:
                    throw new Exception('Encryption failed of method: edit');

                    break;
                case null === $calls['particulars']:
                    throw new Exception('Encryption failed of controller: particulars');

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

    public function callsEdit($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);       
        try {
            $calls = [];

            $calls['hierachy'] = $this->encrypt->scramble('Hierachy\Hierachy\Hierachy', 'controller', $params['userid']);
            $calls['update'] = $this->encrypt->scramble('update', 'method', $params['userid']);
            $calls['delete'] = $this->encrypt->scramble('delete', 'method', $params['userid']);

            switch (true) {
                case null === $calls['hierachy']:
                    throw new Exception('Encryption failed of controller: Hierachy');

                    break;

                case null === $calls['update']:
                    throw new Exception('Encryption failed of method: update');

                    break;

                case null === $calls['delete']:
                    throw new Exception('Encryption failed of method: delete');

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

    public function callsCreate($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);         
        try {
            $calls = [];

            $calls['hierachy'] = $this->encrypt->scramble('Hierachy\Hierachy\Hierachy', 'controller', $params['userid']);
            $calls['store'] = $this->encrypt->scramble('store', 'method', $params['userid']);
            $calls['detail'] = $this->encrypt->scramble('detail', 'method', $params['userid']);
            $calls['delete'] = $this->encrypt->scramble('delete', 'method', $params['userid']);
            $calls['hierachyid'] = $this->encrypt->scramble('0000', 'hierachyid', $params['userid']);
            $calls['hierachysubid'] = $this->encrypt->scramble('0000', 'hierachysubid', $params['userid']);
            $calls['nested'] = $this->encrypt->scramble('0', 'param', $params['userid']);

            switch (true) {
                case null === $calls['hierachy']:
                    throw new Exception('Encryption failed of controller: Hierachy');

                    break;

                case null === $calls['store']:
                    throw new Exception('Encryption failed of method: store');

                    break;

                case null === $calls['delete']:
                    throw new Exception('Encryption failed of method: delete');

                    break;

                case null === $calls['detail']:
                    throw new Exception('Encryption failed of method: detail');

                    break;

                case null === $calls['hierachyid']:
                    throw new Exception('Encryption failed of method: hierachyid');

                    break;

                case null === $calls['hierachysubid']:
                    throw new Exception('Encryption failed of method: hierachysubid');

                    break;

                case null === $calls['nested']:
                    throw new Exception('Encryption failed of method: nested');

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

            $calls['hierachy'] = $this->encrypt->scramble('Hierachy\Base\Hierachy', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['hierachy']:
                    throw new Exception('Encryption failed of controller: Hierachy');

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