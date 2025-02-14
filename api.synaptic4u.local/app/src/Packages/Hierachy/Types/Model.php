<?php

namespace Synaptic4U\Packages\Hierachy\Types;

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
            //     'validate' => serialize($this->validate),
            // ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__ . '()',
                '$e' => $e->__toString(),
            ]);
        }
    }

    public function show($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        $data = null;
        $hierachyid = null;
        $detid = null;

        try {
            if (sizeof($params['formArray']) > 1) {

                $hierachyid = (isset($params['formArray']['hierachyid'])) ? $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid') : $this->encrypt->unscramble($params['formArray'][0], 'hierachyid');
                $detid = (isset($params['formArray']['detid'])) ? $this->encrypt->unscramble($params['formArray']['detid'], 'detid') : $this->encrypt->unscramble($params['formArray'][1], 'detid');
                
                $data['hierachyid'] = [
                    'pass' => null,
                    'message' => null,
                    'value' => $this->encrypt->scramble($hierachyid, 'hierachyid', $params['userid']),
                ];
                $data['detid'] = [
                    'pass' => null,
                    'message' => null,
                    'value' => $this->encrypt->scramble($detid, 'detid', $params['userid']),
                ];

                if (null === $hierachyid) {
                    throw new Exception('Unable to decrypt hierachyid');
                }
                if (null === $detid) {
                    throw new Exception('Unable to decrypt detid');
                }
                if (null === $data['hierachyid']['value']) {
                    throw new Exception('Unable to encrypt hierachyid');
                }
                if (null === $data['detid']['value']) {
                    throw new Exception('Unable to encrypt detid');
                }
            } else {
                throw new Exception("FormArray is too short");
            }
            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'data' => json_encode($data, JSON_PRETTY_PRINT),
            // ]);

            $sql = 'select name from hierachy_det where detid = ?';

            $result = $this->db->query([
                $detid,
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['hierachyname'] = $res->name;
            }

            $sql = 'call GetHierachyTypes(?);';

            $result = $this->db->callProc([
                $hierachyid,
            ], $sql, __METHOD__);

            foreach ($result as $key => $res) {
                $data['types'][$key] = [
                    'exclude' => $res->exclude,
                    'default' => $res->hierachyid,
                    'userid' => $res->userid,
                    'hierachyid' => [
                        'id' => $res->hierachyid,
                        'pass' => null,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->hierachyid, 'hierachyid', $params['userid']),
                    ],
                    'hierachytypeid' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->hierachytypeid, 'hierachytypeid', $params['userid']),
                    ],
                    'type' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->type,
                    ],
                    'datedon' => $res->updatedon,
                    'user' => $res->user,
                ];

                if (null === $data['types'][$key]['hierachytypeid']['value']) {
                    throw new Exception('Unable to encrypt ' . $res->type . ' : ' . $res->hierachytypeid . ' hierachytypeid.');
                }
                if($data['hierachyid']['value'] === null){
                    throw new Exception("Unable to encypt hierachyid");
                }
            }

            $this->log([
                'Location' => __METHOD__ . '() : 2',
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

    public function getTypes($params){
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        $data = null;
        $hierachyid = null;
        $detid = null;

        try {
            if (sizeof($params['formArray']) > 1) {

                $hierachyid = (isset($params['formArray']['hierachyid'])) ? $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid') : $this->encrypt->unscramble($params['formArray'][0], 'hierachyid');
                $detid = (isset($params['formArray']['detid'])) ? $this->encrypt->unscramble($params['formArray']['detid'], 'detid') : $this->encrypt->unscramble($params['formArray'][1], 'detid');

                if (null === $hierachyid) {
                    throw new Exception('Unable to decrypt hierachyid');
                }
                if (null === $detid) {
                    throw new Exception('Unable to decrypt detid');
                }
            } else {
                throw new Exception("FormArray is too short");
            }
            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'data' => json_encode($data, JSON_PRETTY_PRINT),
            // ]);

            $sql = 'call GetHierachyTypes(?);';

            $result = $this->db->callProc([
                $hierachyid,
            ], $sql, __METHOD__);

            foreach ($result as $key => $res) {
                $data['types'][$key] = [
                    'hierachytypeid' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->hierachytypeid, 'hierachytypeid', $params['userid']),
                    ],
                    'type' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->type,
                    ]
                ];

                if (null === $data['types'][$key]['hierachytypeid']['value']) {
                    throw new Exception('Unable to encrypt ' . $res->type . ' : ' . $res->hierachytypeid . ' hierachytypeid.');
                }
            }

            $this->log([
                'Location' => __METHOD__ . '() : 2',
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

    public function store($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        
        $data = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $params['formArray']['hierachyid'] = $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
                $params['formArray']['detid'] = $this->encrypt->unscramble($params['formArray']['detid'], 'detid');

                // $this->log([
                //     'Location' => __METHOD__ . '() : 1',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                // ]);

                if (null === $params['formArray']['hierachyid']) {
                    throw new Exception('Unable to decrypt hierachyid');
                }
                if (null === $params['formArray']['detid']) {
                    throw new Exception('Unable to decrypt detid');
                }
            } else {
                throw new Exception("FormArray is too short");
            }

            $params['formArray']['hierachytypeid'] = 0;
            $params['formArray']['exclude'] = 0;

            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $data = $this->validate->isValid('Types', $params['formArray']);

            // $this->log([
            //     'Location' => __METHOD__ . '() : 2',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'data' => json_encode($data, JSON_PRETTY_PRINT),
            // ]);

            $sql = 'select name from hierachy_det where detid = ?';

            $result = $this->db->query([
                $params['formArray']['detid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['hierachyname'] = $res->name;
            }

            if((int)$data['return'] === 0){
    
                $sql = 'insert into hierachy_type(userid, name, hierachyid, exclude)values(?, ?, ?, ?);';
    
                $result = $this->db->query([
                    $params['userid'],
                    $data['type']['value'],
                    $data['hierachyid']['value'],
                    $data['exclude']['value']
                ], $sql, __METHOD__);

                $data['hierachytypeid']['value'] = $this->db->getLastId();  
            }

            $data['hierachyid']['value'] = $this->encrypt->scramble($params['formArray']['hierachyid'], 'hierachyid', $params['userid']);
            $data['detid']['value'] = $this->encrypt->scramble($params['formArray']['detid'], 'detid', $params['userid']);

            if($data['hierachyid']['value'] === null){
                throw new Exception("Unable to encypt hierachyid");
            }
            if($data['detid']['value'] === null){
                throw new Exception("Unable to encypt detid");
            }

            $this->log([
                'Location' => __METHOD__ . '() : 3',
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

    public function edit($params){
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        
        $data = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $params['formArray']['hierachyid'] = $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
                $params['formArray']['detid'] = $this->encrypt->unscramble($params['formArray']['detid'], 'detid');
                $params['formArray']['hierachytypeid'] = $this->encrypt->unscramble($params['formArray']['hierachytypeid'], 'hierachytypeid');

                // $this->log([
                //     'Location' => __METHOD__ . '() : 1',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                // ]);

                if (null === $params['formArray']['hierachyid']) {
                    throw new Exception('Unable to decrypt hierachyid');
                }
                if (null === $params['formArray']['detid']) {
                    throw new Exception('Unable to decrypt detid');
                }
                if (null === $params['formArray']['hierachytypeid']) {
                    throw new Exception('Unable to decrypt hierachytypeid');
                }
            } else {
                throw new Exception("FormArray is too short");
            }

            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $sql = 'select name from hierachy_det where detid = ?';

            $result = $this->db->query([
                $params['formArray']['detid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['hierachyname'] = $res->name;
            }

            $sql = 'select ht.hierachytypeid, concat(u.firstname, " ", u.surname) as user, ht.name as type, ht.updatedon, ht.exclude 
                      from hierachy_type ht
                      join users u
                        on ht.userid = u.userid
                     where ht.hierachyid = ?
                       and ht.hierachytypeid = ?;';

            $result = $this->db->query([
                $params['formArray']['hierachyid'],
                $params['formArray']['hierachytypeid'],
            ], $sql, __METHOD__);

            foreach($result as $res){
                $data['hierachytypeid'] = [
                    'pass' => null,
                    'value' => $this->encrypt->scramble($res->hierachytypeid, 'hierachytypeid', $params['userid']),
                    'message' => null,
                ];
                $data['type'] = [
                    'pass' => null,
                    'value' => $res->type,
                    'message' => null,
                ];
                $data['exclude'] = [
                    'pass' => null,
                    'value' => $res->exclude,
                    'message' => null,
                ];
                $data['user'] = [
                    'pass' => null,
                    'value' => $res->user,
                    'message' => null,
                ];
                $data['updatedon'] = [
                    'pass' => null,
                    'value' => $res->updatedon,
                    'message' => null,
                ];

                if($data['hierachytypeid']['value'] === null){
                    throw new Exception("Unable to encypt hierachytypeid");
                }
            }

            $data['hierachyid']['value'] = $this->encrypt->scramble($params['formArray']['hierachyid'], 'hierachyid', $params['userid']);
            $data['detid']['value'] = $this->encrypt->scramble($params['formArray']['detid'], 'detid', $params['userid']);

            if($data['hierachyid']['value'] === null){
                throw new Exception("Unable to encypt hierachyid");
            }
            if($data['detid']['value'] === null){
                throw new Exception("Unable to encypt detid");
            }

            $this->log([
                'Location' => __METHOD__ . '() : 3',
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

    public function update($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        
        $data = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $params['formArray']['hierachyid'] = $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
                $params['formArray']['detid'] = $this->encrypt->unscramble($params['formArray']['detid'], 'detid');
                $params['formArray']['hierachytypeid'] = $this->encrypt->unscramble($params['formArray']['hierachytypeid'], 'hierachytypeid');

                // $this->log([
                //     'Location' => __METHOD__ . '() : 1',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                // ]);

                if (null === $params['formArray']['hierachyid']) {
                    throw new Exception('Unable to decrypt hierachyid');
                }
                if (null === $params['formArray']['detid']) {
                    throw new Exception('Unable to decrypt detid');
                }
                if (null === $params['formArray']['hierachytypeid']) {
                    throw new Exception('Unable to decrypt hierachytypeid');
                }
            } else {
                throw new Exception("FormArray is too short");
            }

            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $params['formArray']['exclude'] = ((string)$params['formArray']['exclude'] === 'on') ? 0 : 1;

            $data = $this->validate->isValid('Types', $params['formArray']);

            // $this->log([
            //     'Location' => __METHOD__ . '() : 2',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'data' => json_encode($data, JSON_PRETTY_PRINT),
            // ]);

            $sql = 'select name from hierachy_det where detid = ?';

            $result = $this->db->query([
                $params['formArray']['detid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['hierachyname'] = $res->name;
            }

            if((int)$data['return'] === 0){
    
                $sql = 'update hierachy_type
                           set userid = ?, 
                               name = ?,
                               exclude = ?,
                               updatedon = now()
                         where hierachyid = ?
                           and hierachytypeid = ?;';
    
                $result = $this->db->query([
                    $params['userid'],
                    $data['type']['value'],
                    $data['exclude']['value'],
                    $data['hierachyid']['value'],
                    $data['hierachytypeid']['value']
                ], $sql, __METHOD__);

                $data['rowcount'] = $this->db->getrowCount();
                $data['status'] = $this->db->getStatus();  
            }

            $data['hierachyid']['value'] = $this->encrypt->scramble($params['formArray']['hierachyid'], 'hierachyid', $params['userid']);
            $data['detid']['value'] = $this->encrypt->scramble($params['formArray']['detid'], 'detid', $params['userid']);
            $data['hierachytypeid']['value'] = $this->encrypt->scramble($params['formArray']['hierachytypeid'], 'hierachytypeid', $params['userid']);

            if($data['hierachyid']['value'] === null){
                throw new Exception("Unable to encypt hierachyid");
            }
            if($data['detid']['value'] === null){
                throw new Exception("Unable to encypt detid");
            }
            if($data['hierachytypeid']['value'] === null){
                throw new Exception("Unable to encypt hierachytypeid");
            }

            $this->log([
                'Location' => __METHOD__ . '() : 3',
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

    public function delete($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        
        $data = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $params['formArray']['hierachyid'] = $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
                $params['formArray']['detid'] = $this->encrypt->unscramble($params['formArray']['detid'], 'detid');
                $params['formArray']['hierachytypeid'] = $this->encrypt->unscramble($params['formArray']['hierachytypeid'], 'hierachytypeid');

                // $this->log([
                //     'Location' => __METHOD__ . '() : 1',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                // ]);

                if (null === $params['formArray']['hierachyid']) {
                    throw new Exception('Unable to decrypt hierachyid');
                }
                if (null === $params['formArray']['detid']) {
                    throw new Exception('Unable to decrypt detid');
                }
                if (null === $params['formArray']['hierachytypeid']) {
                    throw new Exception('Unable to decrypt hierachytypeid');
                }
            } else {
                throw new Exception("FormArray is too short");
            }

            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $sql = 'select name from hierachy_det where detid = ?';

            $result = $this->db->query([
                $params['formArray']['detid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['hierachyname'] = $res->name;
            }

            $sql = 'select name as type from hierachy_type where hierachyid = ? and hierachytypeid = ?;';
 
            $result = $this->db->query([
                $params['formArray']['hierachyid'],
                $params['formArray']['hierachytypeid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['type'] = $res->type;
            }

            $sql = 'call GetHierachyDependants(?, ?);';

            $result = $this->db->callProc([
                $params['formArray']['hierachyid'],
                $params['formArray']['hierachytypeid'],
            ], $sql, __METHOD__);

            $data['count'] = $this->db->getrowCount();

            foreach($result as $key => $res){
                $data['orgs'][$key] = [
                        'name' => $res->org,
                    ];
            }

            if((int) $data['count'] === 0){
                $sql = 'delete from hierachy_type where hierachyid = ? and hierachytypeid = ?;';
                
                $result = $this->db->query([
                    $params['formArray']['hierachyid'],
                    $params['formArray']['hierachytypeid'],
                ], $sql, __METHOD__);

                $data['rowcount'] = $this->db->getrowCount();
            }

            $data['hierachyid']['value'] = $this->encrypt->scramble($params['formArray']['hierachyid'], 'hierachyid', $params['userid']);
            $data['detid']['value'] = $this->encrypt->scramble($params['formArray']['detid'], 'detid', $params['userid']);
            $data['hierachytypeid']['value'] = $this->encrypt->scramble($params['formArray']['hierachytypeid'], 'hierachytypeid', $params['userid']);
            
            if($data['hierachyid']['value'] === null){
                throw new Exception("Unable to encypt hierachyid");
            }
            if($data['detid']['value'] === null){
                throw new Exception("Unable to encypt detid");
            }
            if($data['hierachytypeid']['value'] === null){
                throw new Exception("Unable to encypt hierachytypeid");
            }

            $this->log([
                'Location' => __METHOD__ . '() : 3',
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

    public function toggle($params){
        
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        $data = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $params['formArray']['hierachyid'] = $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
                $params['formArray']['detid'] = $this->encrypt->unscramble($params['formArray']['detid'], 'detid');
                $params['formArray']['hierachytypeid'] = $this->encrypt->unscramble($params['formArray']['hierachytypeid'], 'hierachytypeid');

                // $this->log([
                //     'Location' => __METHOD__ . '() : 1',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                // ]);

                if (null === $params['formArray']['hierachyid']) {
                    throw new Exception('Unable to decrypt hierachyid');
                }
                if (null === $params['formArray']['detid']) {
                    throw new Exception('Unable to decrypt detid');
                }
                if (null === $params['formArray']['hierachytypeid']) {
                    throw new Exception('Unable to decrypt hierachytypeid');
                }
            } else {
                throw new Exception("FormArray is too short");
            }

            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $sql = 'select name from hierachy_det where detid = ?';

            $result = $this->db->query([
                $params['formArray']['detid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['hierachyname'] = $res->name;
            }

            $sql = 'call GetHierachyDependants(?, ?);';

            $result = $this->db->callProc([
                $params['formArray']['hierachyid'],
                $params['formArray']['hierachytypeid'],
            ], $sql, __METHOD__);

            $data['count'] = $this->db->getrowCount();

            if((int) $data['count'] === 0){
                $sql = 'update hierachy_type 
                           set exclude = ?, updatedon = now() 
                         where hierachytypeid = ?;';
                
                $result = $this->db->query([
                    ((int)$params['formArray']['exclude'] === 0) ?  1 : 0,
                    $params['formArray']['hierachytypeid'],
                ], $sql, __METHOD__);

                $data['rowcount'] = $this->db->getrowCount();
            }else{
                $sql = 'call GetHierachyDependants(?, ?);';

                $result = $this->db->callProc([
                    $params['formArray']['hierachyid'],
                    $params['formArray']['hierachytypeid'],
                ], $sql, __METHOD__);
    
                foreach($result as $key => $res){
                    $data['orgs'][$key] = [
                        'name' => $res->org,
                    ];
                }
            }          

            $sql = 'select ht.hierachytypeid, ht.userid, case when ht.userid=3 then "System Default" else concat(u.firstname, " ", u.surname) end as user, ht.name as type, ht.updatedon, ht.exclude
                      from hierachy_type ht
                      join users u
                        on ht.userid = u.userid
                     where ht.hierachytypeid = ?';

            $result = $this->db->query([
                $params['formArray']['hierachytypeid'],
            ], $sql, __METHOD__);

            foreach($result as $res){
                $data['user'] = $res->user;
                $data['userid'] = $res->userid;
                $data['type']['value'] = $res->type;
                $data['datedon'] = $res->updatedon;
                $data['exclude'] = $res->exclude;
                $data['hierachytypeid']['value'] = $this->encrypt->scramble($res->hierachytypeid, 'hierachytypeid', $params['userid']);

                if($data['hierachytypeid']['value'] === null){
                    throw new Exception("Unable to encypt hierachytypeid");
                }
            }
            
            $data['hierachyid']['value'] = $this->encrypt->scramble($params['formArray']['hierachyid'], 'hierachyid', $params['userid']);
            $data['detid']['value'] = $this->encrypt->scramble($params['formArray']['detid'], 'detid', $params['userid']);

            if($data['hierachyid']['value'] === null){
                throw new Exception("Unable to encypt hierachyid");
            }
            if($data['detid']['value'] === null){
                throw new Exception("Unable to encypt detid");
            }

            $this->log([
                'Location' => __METHOD__ . '() : 3',
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

    public function callsToggle($params){
        
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        try {
            $calls = [];

            $calls['types'] = $this->encrypt->scramble('Hierachy\Types\Types', 'controller', $params['userid']);
            $calls['toggle'] = $this->encrypt->scramble('toggle', 'method', $params['userid']);
            $calls['edit'] = $this->encrypt->scramble('edit', 'method', $params['userid']);

            switch (true) {
                case null === $calls['types']:
                    throw new Exception('Encryption failed of controller: types');

                    break;

                    case null === $calls['toggle']:
                        throw new Exception('Encryption failed of method: toggle');
    
                        break;

                        case null === $calls['edit']:
                            throw new Exception('Encryption failed of method: edit');
        
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

            $calls['types'] = $this->encrypt->scramble('Hierachy\Types\Types', 'controller', $params['userid']);
            $calls['edit'] = $this->encrypt->scramble('edit', 'method', $params['userid']);
            $calls['store'] = $this->encrypt->scramble('store', 'method', $params['userid']);
            $calls['toggle'] = $this->encrypt->scramble('toggle', 'method', $params['userid']);
            $calls['roles'] = $this->encrypt->scramble('Hierachy\Roles\Roles', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);
            
            switch (true) {
                case null === $calls['types']:
                    throw new Exception('Encryption failed of controller: types');

                    break;

                case null === $calls['edit']:
                    throw new Exception('Encryption failed of method: edit');

                    break;

                case null === $calls['store']:
                    throw new Exception('Encryption failed of method: store');

                    break;

                    case null === $calls['toggle']:
                        throw new Exception('Encryption failed of method: toggle');
    
                        break;

                        case null === $calls['roles']:
                            throw new Exception('Encryption failed of method: roles');
        
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

    public function callsEdit($params){
        
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        try {
            $calls = [];

            $calls['types'] = $this->encrypt->scramble('Hierachy\Types\Types', 'controller', $params['userid']);
            $calls['update'] = $this->encrypt->scramble('update', 'method', $params['userid']);
            $calls['delete'] = $this->encrypt->scramble('delete', 'method', $params['userid']);

            switch (true) {
                case null === $calls['types']:
                    throw new Exception('Encryption failed of controller: types');

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

    public function callsStored($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        
        try {
            $calls = [];

            $calls['types'] = $this->encrypt->scramble('Hierachy\Types\Types', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['types']:
                    throw new Exception('Encryption failed of controller: types');

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

    public function callsDeleted($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        
        try {
            $calls = [];

            $calls['types'] = $this->encrypt->scramble('Hierachy\Types\Types', 'controller', $params['userid']);
            $calls['edit'] = $this->encrypt->scramble('edit', 'method', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);
            $calls['toggle'] = $this->encrypt->scramble('toggle', 'method', $params['userid']);

            switch (true) {
                case null === $calls['types']:
                    throw new Exception('Encryption failed of controller: types');

                    break;

                    case null === $calls['edit']:
                        throw new Exception('Encryption failed of method: edit');
    
                        break;

                        case null === $calls['show']:
                            throw new Exception('Encryption failed of method: show');
        
                            break;

                            case null === $calls['toggle']:
                                throw new Exception('Encryption failed of method: toggle');
            
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

    public function callsUpdated($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        
        try {
            $calls = [];

            $calls['types'] = $this->encrypt->scramble('Hierachy\Types\Types', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['types']:
                    throw new Exception('Encryption failed of controller: types');

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