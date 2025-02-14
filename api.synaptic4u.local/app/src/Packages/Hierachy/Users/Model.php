<?php

namespace Synaptic4U\Packages\Hierachy\Users;

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

    public function list($params)
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
            //     'hierachyid' => $hierachyid,
            //     'detid' => $detid,
            // ]);

            $sql = 'select hu.userid
                      from hierachy_users hu
                      join users u
                        on hu.userid = u.userid
                      join hierachy_roles hr
                        on hu.roleid = hr.roleid
                     where hu.hierachyid = ?
                     order by u.firstname, u.surname ASC;';

            $result = $this->db->query([
                $hierachyid,
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $list[] = $res->userid;
            }

            $userids_chunked = array_chunk($list, 20);

            foreach($userids_chunked as $chunk){
                $data['usersList'][] = $this->encrypt->scramble(implode(",",$chunk), 'userids', $params['userid']);
            }

            $this->log([
                'Location' => __METHOD__ . '() : 2',
                'hierachyid' => $hierachyid,
                'detid' => $detid,
                'data' => json_encode($data, JSON_PRETTY_PRINT),
                'userids_chunked' => json_encode($userids_chunked, JSON_PRETTY_PRINT),
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

    public function page($params, $list = null)
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
                
                $userids_string = ($list === null) ? $this->encrypt->unscramble($params['formArray']['userids'], 'userids') : $this->encrypt->unscramble($list, 'userids');
                $userids_string = explode(",",$userids_string);
                
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
            //     'is string userids_string' => is_string($userids_string),
            //     'userids_string' => $userids_string,
            //     'list' => $list,
            // ]);

            $sql_params = null;
            $userids_params = $hierachyid.','.implode(",",$userids_string);
            for($x = 0; $x < sizeof($userids_string);$x++){
                $sql_params .= ($x === (sizeof($userids_string)-1))? 'hu.userid = ?' : 'hu.userid = ? or ';
                
            }
            $sql = 'select hu.hierachyid, hu.userid, hu.roleid, hr.name as role, hu.updatedon, concat(u.firstname," ", u.surname) as user, u.email, hu.personnel, hu.invite
                      from hierachy_users hu
                      join users u
                        on hu.userid = u.userid
                      join hierachy_roles hr
                        on hu.roleid = hr.roleid
                     where hu.hierachyid = ?
                       and ('.$sql_params.');';

            $result = $this->db->query(explode(",",$userids_params), $sql, __METHOD__);

            foreach ($result as $key => $res) {
                $data['users'][$key] = [
                    'userid' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->userid, 'userid', $params['userid'], __METHOD__),
                    ],
                    'role' => [
                        'id' => $res->roleid,
                        'pass' => null,
                        'message' => null,
                        'value' => $res->role,
                    ],
                    'updatedon' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->updatedon,
                    ],
                    'user' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->user,
                    ],
                    'email' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->email,
                    ],
                    'personnel' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->personnel,
                    ],
                    'invite' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->invite,
                    ],
                ];

                if (null === $data['users'][$key]['userid']['value']) {
                    throw new Exception('Unable to encrypt ' . $res->user . ' : ' . $res->userid . ' userid.');
                }
            }

            $this->log([
                'Location' => __METHOD__ . '() : 2',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
                'userids_string' => json_encode($userids_string, JSON_PRETTY_PRINT),
                'userids_params' => json_encode($userids_params, JSON_PRETTY_PRINT),
                'params' => json_encode($params, JSON_PRETTY_PRINT),
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

            $sql = 'select name from hierachy_det where detid = ?';

            $result = $this->db->query([
                $detid,
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['hierachyname'] = $res->name;
            }

            $this->log([
                'Location' => __METHOD__ . '() : 0',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
                'hierachyid' =>$hierachyid,
                'detid' =>$detid,
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
                $params['formArray']['roleid'] = $this->encrypt->unscramble($params['formArray']['roleid'], 'roleid');

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
                if (null === $params['formArray']['roleid']) {
                    throw new Exception('Unable to decrypt roleid');
                }
            } else {
                throw new Exception("FormArray is too short");
            }

            $params['formArray']['userid'] = 0;
            $params['formArray']['personnel'] = ((string)$params['formArray']['personnel'] === 'off') ? 0 : 1;
            $params['formArray']['invite'] = ($params['formArray']['personnel'] === 0) ? 1 : 0;

            // $this->log([
            //     'Location' => __METHOD__ . '() : 2',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $data = $this->validate->isValid('User', $params['formArray']);

            // $this->log([
            //     'Location' => __METHOD__ . '() : 3',
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
                $data['users_exists'] = null;

                $sql = 'select count(*) as count from users where email = ?;';

                $result = $this->db->query([
                    $data['email']['value']
                ], $sql, __METHOD__);

                foreach($result as $res){
                    $data['users_exists'] = $res->count;
                }

                if((int)$data['users_exists'] === 1){
                    $sql = 'select userid from users where email = ?;';

                    $result = $this->db->query([
                        $data['email']['value']
                    ], $sql, __METHOD__);

                    foreach($result as $res){
                        $data['userid']['value'] = $res->userid;
                    }
                }

                if((int)$data['users_exists'] === 0){
                    $sql = 'insert into users(firstname, surname, email, contactnu, active)values(?, ?, ?, ?, ?);';
        
                    $result = $this->db->query([
                        $data['firstname']['value'],
                        $data['surname']['value'],
                        $data['email']['value'],
                        $data['contactnu']['value'],
                        0
                    ], $sql, __METHOD__);

                    $data['userid']['value'] = $this->db->getLastId();

                    if((int)$data['invite']['value'] === 1){
                        $sql = 'insert into invites(hierachyid, from_userid, to_userid, appid, email)values(?, ?, ?, ?, ?);';

                        $result = $this->db->query([
                            $data['hierachyid']['value'],
                            $params['userid'],
                            $data['userid']['value'],
                            null,
                            $data['email']['value'],
                        ], $sql, __METHOD__);
    
                        $data['inviteid'] = $this->db->getLastId();
                    }
                }
    
                $sql = 'select count(*) as count from hierachy_users where userid = ? and hierachyid = ?';

                $result = $this->db->query([
                    $data['userid']['value'],
                    $data['hierachyid']['value'],
                ], $sql, __METHOD__);

                foreach($result as $res){
                    $data['hierachy_exists'] = $res->count;
                }

                if((int)$data['hierachy_exists'] === 0){
                    $sql = 'insert into hierachy_users(hierachyid, userid, maintainedby, roleid, personnel, invite)values(?, ?, ?, ?, ?, ?);';

                    $result = $this->db->query([
                        $data['hierachyid']['value'],
                        $data['userid']['value'],
                        $params['userid'],
                        $data['roleid']['value'],
                        $data['personnel']['value'],
                        $data['invite']['value'],
                    ], $sql, __METHOD__);

                    $data['success'] = $this->db->getrowCount();
                }
            }

            if((int)$data['users_exists'] > 0 && (int)$data['invite'] === 1){
                $sql = 'insert into notifications(from_userid, to_userid, hierachyid, appid, request)values(?, ?, ?, null, ?)';

                $result = $this->db->query([
                    $params['userid'],
                    $data['userid']['value'],
                    $data['hierachyid']['value'],
                    null,
                    "You have been invited to join: ".$data['hierachyname']
                ], $sql, __METHOD__);

                $data['noteid'] = $this->db->getLastId();
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
                'Location' => __METHOD__ . '() : 4',
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
                $params['formArray']['hierachyid'] = $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
                $params['formArray']['detid'] = $this->encrypt->unscramble($params['formArray']['detid'], 'detid');
                $params['formArray']['userid'] = $this->encrypt->unscramble($params['formArray']['userid'], 'userid');

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
                if (null === $params['formArray']['userid']) {
                    throw new Exception('Unable to decrypt userid');
                }
            } else {
                throw new Exception("FormArray is too short");
            }

            $sql = 'select name from hierachy_det where detid = ?';

            $result = $this->db->query([
                $params['formArray']['detid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['hierachyname'] = $res->name;
            }

            $sql = 'select hu.userid, u.firstname, u.surname, u.email, u.contactnu, 
                           concat(uu.firstname,  " ", uu.surname) as maintainedby, 
                           hu.roleid, hu.updatedon, hu.personnel, hu.invite
                      from hierachy_users hu
                      join users u
                        on hu.userid = u.userid
                      join users uu
                        on hu.maintainedby = uu.userid
                     where hu.userid = ?
                       and hu.hierachyid = ?;';

            $result = $this->db->query([
                $params['formArray']['userid'],
                $params['formArray']['hierachyid'],
            ], $sql, __METHOD__);

            foreach($result as $res){
                $data['user'] = [
                    'userid' => [
                        'pass' => null,
                        'value' => $this->encrypt->scramble($res->userid, 'userid', $params['userid']),
                        'message' => null,
                    ],
                    'firstname' => [
                        'pass' => null,
                        'value' => $res->firstname,
                        'message' => null,
                    ],
                    'surname' => [
                        'pass' => null,
                        'value' => $res->surname,
                        'message' => null,
                    ],
                    'email' => [
                        'pass' => null,
                        'value' => $res->email,
                        'message' => null,
                    ],
                    'contactnu' => [
                        'pass' => null,
                        'value' => $res->contactnu,
                        'message' => null,
                    ],
                    'maintainedby' => [
                        'pass' => null,
                        'value' => $res->maintainedby,
                        'message' => null,
                    ],
                    'updatedon' => [
                        'pass' => null,
                        'value' => $res->updatedon,
                        'message' => null,
                    ],
                    'roleid' => [
                        'pass' => null,
                        'value' => $res->roleid,
                        'message' => null,
                    ],
                    'personnel' => [
                        'pass' => null,
                        'value' => $res->personnel,
                        'message' => null,
                    ],
                    'invite' => [
                        'pass' => null,
                        'value' => $res->invite,
                        'message' => null,
                    ],
                ];

                if($data['user']['userid']['value'] === null){
                    throw new Exception("Unable to encypt userid");
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
                $params['formArray']['userid'] = $this->encrypt->unscramble($params['formArray']['userid'], 'userid');
                $params['formArray']['roleid'] = $this->encrypt->unscramble($params['formArray']['roleid'], 'roleid');

                // $this->log([
                //     'Location' => __METHOD__ . '() : 0',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                // ]);

                if (null === $params['formArray']['hierachyid']) {
                    throw new Exception('Unable to decrypt hierachyid');
                }
                if (null === $params['formArray']['detid']) {
                    throw new Exception('Unable to decrypt detid');
                }
                if (null === $params['formArray']['userid']) {
                    throw new Exception('Unable to decrypt userid');
                }
                if (null === $params['formArray']['roleid']) {
                    throw new Exception('Unable to decrypt roleid');
                }
            } else {
                throw new Exception("FormArray is too short");
            }

            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $params['formArray']['personnel'] = ((string)$params['formArray']['personnel'] === 'off') ? 0 : 1;
            $params['formArray']['invite'] = ($params['formArray']['personnel'] === 0) ? 1 : 0;

            $data = $this->validate->isValid('User', $params['formArray']);

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
                $sql = 'update users
                           set firstname = ?,
                               surname = ?,
                               contactnu = ?,
                               email = ?
                         where userid = ?';

                $result = $this->db->query([
                    $data['firstname']['value'],
                    $data['surname']['value'],
                    $data['contactnu']['value'],
                    $data['email']['value'],
                    $data['userid']['value'],
                ], $sql, __METHOD__);

                $data['count'] = $this->db->getrowCount();

                $sql = 'select invite 
                          from hierachy_users 
                         where userid = ? 
                           and hierachyid = ?;';

                $result = $this->db->query([
                    $data['userid']['value'],
                    $data['hierachyid']['value'],
                ], $sql, __METHOD__);

                foreach ($result as $res) {
                    $data['invite']['value'] = ((int)$res->invite === 2) ? $res->invite : $data['invite']['value'];
                }

                $data['invite']['value'] = ((int)$data['personnel']['value'] === 1) ? 0 : $data['invite']['value'];

                $sql = 'update hierachy_users
                           set maintainedby = ?,
                               roleid = ?,
                               personnel = ?,
                               invite = ?,
                               updatedon = now() 
                         where userid = ?
                           and hierachyid = ?;';
                
                $result = $this->db->query([
                    $params['userid'],
                    $data['roleid']['value'],
                    $data['personnel']['value'],
                    $data['invite']['value'],
                    $data['userid']['value'],
                    $data['hierachyid']['value'],
                ], $sql, __METHOD__);

                $data['rowcount'] = $this->db->getrowCount();

                $sql = 'select active 
                          from users 
                         where userid = ?;';

                $result = $this->db->query([
                    $data['userid']['value'],
                ], $sql, __METHOD__);

                foreach($result as $res){
                    $data['active'] = $res->invite;
                }

                if((int)$data['invite']['value'] === 1 && (int)$data['active'] === 0){
                    $sql = 'insert into invites(hierachyid, from_userid, to_userid, appid, email)values(?, ?, ?, ?, ?);';

                    $result = $this->db->query([
                        $data['hierachyid']['value'],
                        $params['userid'],
                        $data['userid']['value'],
                        null,
                        $data['email']['value'],
                    ], $sql, __METHOD__);

                    $data['inviteid'] = $this->db->getLastId();
                }

                if((int)$data['active'] === 1 && (int)$data['invite'] === 1){
                    $sql = 'insert into notifications(from_userid, to_userid, hierachyid, appid, request)values(?, ?, ?, null, ?)';
    
                    $result = $this->db->query([
                        $params['userid'],
                        $data['userid']['value'],
                        $data['hierachyid']['value'],
                        null,
                        "You have been invited to join: ".$data['hierachyname']
                    ], $sql, __METHOD__);
    
                    $data['noteid'] = $this->db->getLastId();
                }
            }

            $data['hierachyid']['value'] = $this->encrypt->scramble($params['formArray']['hierachyid'], 'hierachyid', $params['userid']);
            $data['detid']['value'] = $this->encrypt->scramble($params['formArray']['detid'], 'detid', $params['userid']);
            $data['roleid']['value'] = $this->encrypt->scramble($data['roleid']['value'], 'roleid', $params['userid']);

            if($data['hierachyid']['value'] === null){
                throw new Exception("Unable to encypt hierachyid");
            }
            if($data['detid']['value'] === null){
                throw new Exception("Unable to encypt detid");
            }
            if($data['roleid']['value'] === null){
                throw new Exception("Unable to encypt roleid");
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
                $params['formArray']['userid'] = $this->encrypt->unscramble($params['formArray']['userid'], 'userid');

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
                if (null === $params['formArray']['userid']) {
                    throw new Exception('Unable to decrypt userid');
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

            $sql = 'select active, concat(firstname,  " ", surname) as user
                      from users 
                     where userid = ?;';
 
            $result = $this->db->query([
                $params['formArray']['userid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['active'] = $res->active;
                $data['user'] = $res->user;
            }

            $sql = 'delete from hierachy_users where userid = ? and hierachyid = ?;';

            $result = $this->db->query([
                $params['formArray']['userid'],
                $params['formArray']['hierachyid'],
            ], $sql, __METHOD__);

            $data['hierachy_users_count'] = $this->db->getrowCount();

            if((int) $data['active'] === 0){
                $sql = 'update users set deleted = 1 where userid = ?;';
                
                $result = $this->db->query([
                    $params['formArray']['userid'],
                ], $sql, __METHOD__);

                $data['users_count'] = $this->db->getrowCount();
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

    public function callsShow($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        try {
            $calls = [];

            $calls['users'] = $this->encrypt->scramble('Hierachy\Users\Users', 'controller', $params['userid']);
            $calls['store'] = $this->encrypt->scramble('store', 'method', $params['userid']);
            $calls['approles'] = $this->encrypt->scramble('Hierachy\AppRoles\AppRoles', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['users']:
                    throw new Exception('Encryption failed of controller: users');

                    break;

                case null === $calls['store']:
                    throw new Exception('Encryption failed of method: store');

                    break;

                case null === $calls['approles']:
                    throw new Exception('Encryption failed of controller: approles');

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

    public function callsPage($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        try {
            $calls = [];

            $calls['users'] = $this->encrypt->scramble('Hierachy\Users\Users', 'controller', $params['userid']);
            $calls['edit'] = $this->encrypt->scramble('edit', 'method', $params['userid']);

            switch (true) {
                case null === $calls['users']:
                    throw new Exception('Encryption failed of controller: users');

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

    public function callsList($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        try {
            $calls = [];

            $calls['users'] = $this->encrypt->scramble('Hierachy\Users\Users', 'controller', $params['userid']);
            $calls['page'] = $this->encrypt->scramble('page', 'method', $params['userid']);

            switch (true) {
                case null === $calls['users']:
                    throw new Exception('Encryption failed of controller: users');

                    break;

                case null === $calls['page']:
                    throw new Exception('Encryption failed of method: page');

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

            $calls['users'] = $this->encrypt->scramble('Hierachy\Users\Users', 'controller', $params['userid']);
            $calls['update'] = $this->encrypt->scramble('update', 'method', $params['userid']);
            $calls['delete'] = $this->encrypt->scramble('delete', 'method', $params['userid']);

            switch (true) {
                case null === $calls['users']:
                    throw new Exception('Encryption failed of controller: users');

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

            $calls['users'] = $this->encrypt->scramble('Hierachy\Users\Users', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['users']:
                    throw new Exception('Encryption failed of controller: users');

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

            $calls['users'] = $this->encrypt->scramble('Hierachy\Users\Users', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['users']:
                    throw new Exception('Encryption failed of controller: users');

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

    public function callsUpdated($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);  
        try {
            $calls = [];

            $calls['users'] = $this->encrypt->scramble('Hierachy\Users\Users', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['users']:
                    throw new Exception('Encryption failed of controller: users');

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