<?php

namespace Synaptic4U\Packages\User;

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
            $path = dirname(__FILE__, 1).'/Models/';

            $this->encrypt = new Encryption();

            $this->db = new DB();

            $this->validate = new Validate($path);

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'Dir' => dirname(__FILE__, 1),
            //     'path' => $path,
            //     'validate' => serialize($this->validate),
            // ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            return null;
        }
    }

    public function create($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $data = [
            'email_exists' => null,
            'firstname' => [
                'value' => null,
                'message' => null,
                'pass' => null,
            ],
            'surname' => [
                'value' => null,
                'message' => null,
                'pass' => null,
            ],
            'email' => [
                'value' => null,
                'message' => null,
                'pass' => null,
            ],
            'contactnu' => [
                'value' => null,
                'message' => null,
                'pass' => null,
            ],
            'passkey' => [
                'value' => null,
                'message' => null,
                'pass' => null,
            ],
            'popi_compliance' => [
                'value' => null,
                'message' => null,
                'pass' => null,
            ],
        ];

        try {
            $sql = 'select userid, firstname, surname, email, contactnu, popi_compliance
                      from users
                     where userid = ?';

            $userid = (isset($params['formArray'][0])) ? $this->encrypt->unscramble($params['formArray'][0], 'userid') : $params['userid'];

            $result = $this->db->query([
                $userid,
            ], $sql);

            foreach ($result as $res) {
                $data['userid'] = $this->encrypt->scramble($res->userid, 'userid');
                $data['firstname']['value'] = $res->firstname;
                $data['surname']['value'] = $res->surname;
                $data['contactnu']['value'] = $res->contactnu;
                $data['email']['value'] = $res->email;
                $data['passkey']['value'] = '';
                $data['popi_compliance']['value'] = $res->popi_compliance;
            }


            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]); 
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            $data = null;
        } finally {
            return $data;
        }
    }
    
    public function invite($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $data = [
            'firstname' => [
                'value' => null,
                'message' => null,
                'pass' => null,
            ],
            'surname' => [
                'value' => null,
                'message' => null,
                'pass' => null,
            ],
            'email' => [
                'value' => null,
                'message' => null,
                'pass' => null,
            ],
            'contactnu' => [
                'value' => null,
                'message' => null,
                'pass' => null,
            ],
            'passkey' => [
                'value' => null,
                'message' => null,
                'pass' => null,
            ],
            'popi_compliance' => [
                'value' => null,
                'message' => null,
                'pass' => null,
            ],
        ];

        try {
            $sql = 'select u.userid, u.firstname, u.surname, u.email, u.contactnu, u.popi_compliance,
                           hd.hierachyid, hd.detid, hd.name as org, concat(uu.firstname, " ",  uu.surname) as invitedby
                      from invites i
                      join users u
                        on i.to_userid = u.userid
                      join users uu
                        on i.from_userid = uu.userid
                      left join hierachy_users hu
                        on i.to_userid = hu.userid
                      left join hierachy_det hd
                        on i.hierachyid = hd.hierachyid
                     where i.to_userid = ?;';

            $result = $this->db->query([
                $params['userid'],
            ], $sql);

            foreach ($result as $res) {
                $data['userid'] = $this->encrypt->scramble($res->userid, 'userid', $params['userid']);
                $data['firstname']['value'] = $res->firstname;
                $data['surname']['value'] = $res->surname;
                $data['contactnu']['value'] = $res->contactnu;
                $data['email']['value'] = $res->email;
                $data['passkey']['value'] = '';
                $data['popi_compliance']['value'] = $res->popi_compliance;
                $data['hierachyid'] = $this->encrypt->scramble($res->hierachyid, 'hierachyid', $params['userid']);
                $data['detid'] = $this->encrypt->scramble($res->detid, 'detid', $params['userid']);
                $data['org'] = $res->org;
                $data['invitedby'] = $res->invitedby;
            }

            if(is_null($data['userid'])){
                throw new Exception("Error encrypting userid");                
            }
            if(is_null($data['hierachyid'])){
                throw new Exception("Error encrypting hierachyid");                
            }
            if(is_null($data['detid'])){
                throw new Exception("Error encrypting detid");                
            }
            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]); 
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            $data = null;
        } finally {
            return $data;
        }
    }

    public function inviteStore($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $data = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $params['formArray']['hierachyid'] = $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
                $params['formArray']['detid'] = $this->encrypt->unscramble($params['formArray']['detid'], 'detid');
                $params['formArray']['userid'] = $this->encrypt->unscramble($params['formArray']['userid'], 'userid');
                $params['formArray']['popi_compliance'] = ((string) $params['formArray']['popi_compliance'] === (string) 'on') ? 1 : 0;

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
            } else {
                throw new Exception("FormArray is too short");
            }

            // $this->log([
            //     'Location' => __METHOD__.'(): 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $data = $this->validate->isValid('User', $params['formArray']);

            if (!is_null($data)) {
                // $this->log([
                //     'Location' => __METHOD__.'(): 2',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                // ]);

                if ($data['return'] > 0) {
                    return $data;
                }

                $sql = 'update users
                           set firstname = ?, 
                               surname = ?, 
                               contactnu = ?, 
                               email = ?, 
                               passkey = ?, 
                               popi_compliance = ?, 
                               active = ?,
                               modifiedon = now()
                         where userid = ?;';

                $result = $this->db->query([
                    $data['firstname']['value'],
                    $data['surname']['value'],
                    $data['contactnu']['value'],
                    $data['email']['value'],
                    $this->encrypt->hashString($data['passkey']['value']),
                    $data['popi_compliance']['value'],
                    1,
                    $params['formArray']['userid']
                ], $sql);

                $user_rowcount = $this->db->getrowCount();

                if((int)$user_rowcount > 0){
                    $sql = 'update invites
                               set accepted = ?
                             where to_userid = ?
                               and hierachyid = ?';

                    $result = $this->db->query([
                        1,
                        $params['formArray']['userid'],
                        $params['formArray']['hierachyid'],
                    ], $sql);

                    $invites_rowcount = $this->db->getrowCount();

                    $sql = 'update hierachy_users
                               set invite = ?
                             where hierachyid = ?
                               and userid = ?;';

                    $result = $this->db->query([
                        2,
                        $params['formArray']['hierachyid'],
                        $params['formArray']['userid'],
                    ], $sql);

                    $invites_rowcount += $this->db->getrowCount();

                    $sql = 'select from_userid 
                              from invites 
                             where hierachyid = ?
                               and to_userid = ?;';

                    $result = $this->db->query([
                        $params['formArray']['hierachyid'],
                        $params['formArray']['userid'],
                    ], $sql);

                    foreach ($result as $res) {
                        $to_userid = $res->from_userid;
                    }

                    $sql = 'select name as org
                              from hierachy_det
                             where hierachyid = ?
                               and detid = ?;';

                    $result = $this->db->query([
                        $params['formArray']['hierachyid'],
                        $params['formArray']['detid'],
                    ], $sql);

                    foreach($result as $res){
                        $org = $res->org;
                    }

                    $request = $data['firstname']['value'].' '.$data['surname']['value'].' has accepted your request to join '.$org;

                    $sql = 'insert into notifications(hierachyid, from_userid, to_userid, request, datedon)values(?, ?, ?, ?, now())';

                    $result = $this->db->query([
                        $params['formArray']['hierachyid'],
                        $params['formArray']['userid'],
                        $to_userid,
                        $request,
                    ], $sql);

                    $noteid = $this->db->getLastId();
                }

                $this->log([
                    'Location' => __METHOD__.'(): 3',
                    'params' => json_encode($params, JSON_PRETTY_PRINT),
                    'data' => json_encode($data, JSON_PRETTY_PRINT),
                    'user_rowcount' => $user_rowcount,
                    'invites_rowcount' => $invites_rowcount,
                    'noteid' => $noteid,
                    'to_userid' => $to_userid,
                    'request' => $request,
                ]);
            } else {
                throw new Exception('Could not validate user');
            }
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            $data = null;
        } finally {
            return $data;
        }   
    }

    public function store($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $data = null;

        try {
            $params['formArray']['popi_compliance'] = ((string) $params['formArray']['popi_compliance'] === (string) 'on') ? 1 : 0;

            // $this->log([
            //     'Location' => __METHOD__.'(): 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $data = $this->validate->isValid('User', $params['formArray']);

            // $this->log([
            //     'Location' => __METHOD__.'(): DEBUG',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'data' => json_encode($data, JSON_PRETTY_PRINT),
            // ]);

            if (!is_null($data)) {
                // $this->log([
                //     'Location' => __METHOD__.'(): 2',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                // ]);

                if ($data['return'] > 0) {
                    return $data;
                }

                $sql = 'select count(*) as email_exists from users where email = ?';

                $result = $this->db->query([
                    $data['email']['value'],
                ], $sql);

                foreach ($result as $res) {
                    $data['email_exists'] = $res->email_exists;
                }

                if ($data['email_exists'] > 0) {
                    $data['email']['pass'] = 1;
                    $data['email']['message'] = 'is-invalid';

                    return $data;
                }

                // $this->log([
                //     'Location' => __METHOD__.'(): 3',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                // ]);

                $sql = 'insert into users(firstname, surname, contactnu, email, passkey, popi_compliance, active)
                            values(?, ?, ?, ?, ?, ?, 1);';

                $result = $this->db->query([
                    $data['firstname']['value'],
                    $data['surname']['value'],
                    $data['contactnu']['value'],
                    $data['email']['value'],
                    $this->encrypt->hashString($data['passkey']['value']),
                    $data['popi_compliance']['value'],
                ], $sql);

                // $this->log([
                //     'Location' => __METHOD__.'(): 4',
                //     'UserId' => $this->db->getLastId(),
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                // ]);

                $data = null;

                $data['return'] = 0;

                $data['userid'] = $this->db->getLastId();
            } else {
                throw new Exception('Could not validate user');
            }

            if (null === $data) {
                throw new Exception('Could not insert user');
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]); 
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            $data = null;
        } finally {
            return $data;
        }
    }

    public function update($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $data = null;

        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     'params' => json_encode($params),
        // ]);

        try {
            $params['formArray']['popi_compliance'] = 1;

            $data = $this->validate->isValid('User', $params['formArray']);

            if (!is_null($data)) {
                // $this->log([
                //     'Location' => __METHOD__.'(): 2',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                // ]);

                if ($data['return'] > 0) {
                    return $data;
                }

                $sql = 'select count(*) as email_exists from users where email = ?';

                $result = $this->db->query([
                    $data['email']['value'],
                ], $sql);

                foreach ($result as $res) {
                    $data['email_exists'] = $res->email_exists;
                }

                if ($data['email_exists'] > 0) {
                    $data['email']['pass'] = 1;
                    $data['email']['message'] = 'is-invalid';

                    return $data;
                }

                // $this->log([
                //     'Location' => __METHOD__.'(): 3',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                // ]);

                $sql = ' update users
                        set firstname = ?, 
                            surname = ?, 
                            email = ?, 
                            contactnu = ?, 
                            passkey = ?
                      where userid = ?';

                $result = $this->db->query([
                    $data['firstname']['value'],
                    $data['surname']['value'],
                    $data['email']['value'],
                    $data['contactnu']['value'],
                    $this->encrypt->hashString($data['passkey']['value']),
                    $params['userid'],
                ], $sql);

                // $this->log([
                //     'Location' => __METHOD__.'(): 4',
                //     'UserId' => $this->db->getLastId(),
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                // ]);

                $data = null;

                $data['return'] = 0;

                $data['userid'] = $this->db->getLastId();
                $this->log([
                    'Location' => __METHOD__.'()',
                    'params' => json_encode($params, JSON_PRETTY_PRINT),
                    'data' => json_encode($data, JSON_PRETTY_PRINT),
                ]); 
            } else {
                throw new Exception('Could not validate user');
            }

            if (null === $data) {
                throw new Exception('Could not update user');
            }
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            $data = null;
        } finally {
            return $data;
        }
    }

    public function retrieve($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $data = [
            'userid' => null,
            'firstname' => [
                'value' => null,
                'message' => '',
                'pass' => 0,
            ],
            'surname' => [
                'value' => null,
                'message' => '',
                'pass' => 0,
            ],
            'email' => [
                'value' => null,
                'message' => '',
                'pass' => 0,
            ],
            'contactnu' => [
                'value' => null,
                'message' => '',
                'pass' => 0,
            ],
            'passkey' => [
                'value' => null,
                'message' => '',
                'pass' => 0,
            ],
            'popi_compliance' => [
                'value' => null,
                'message' => '',
                'pass' => 0,
            ],
        ];

        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     'params' => json_encode($params),
        //     'is formArray[0]' => (isset($params['formArray'][0])) ? 'True' : 'false',
        // ]);

        try {
            $sql = 'select userid, firstname, surname, email, contactnu, popi_compliance
                      from users
                     where userid = ?';

            $userid = (isset($params['formArray'][0])) ? $this->encrypt->unscramble($params['formArray'][0], 'userid') : $params['userid'];

            $result = $this->db->query([
                $userid,
            ], $sql);

            foreach ($result as $res) {
                $data['userid'] = $this->encrypt->scramble($res->userid, 'userid');
                $data['firstname']['value'] = $res->firstname;
                $data['surname']['value'] = $res->surname;
                $data['contactnu']['value'] = $res->contactnu;
                $data['email']['value'] = $res->email;
                $data['passkey']['value'] = '';
                $data['popi_compliance']['value'] = $res->popi_compliance;
            }
            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]); 
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            $data = null;
        } finally {
            return $data;
        }
    }

    public function getUser($params, $userid = 0)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $data = null;

        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     'params' => json_encode($params),
        // ]);

        try {
            if((int)$userid > 0){
                $sql = 'select concat(firstname, " ", surname) as user from users where userid = ?';

                $result = $this->db->query([
                    $userid,
                ], $sql);
    
                foreach ($result as $res) {
                    $data = $res->user;
                }
            }else{
                $data['userid'] = $this->encrypt->scramble($params['userid'], 'userid');

                $sql = 'select u.firstname, u.surname, case when isnull(p.profile) then "default" else p.profile end as profile
                          from users u
                          left join profile_users p
                            on u.userid = p.userid
                         where u.userid = ?;';
    
                $result = $this->db->query([
                    $params['userid'],
                ], $sql);
    
                foreach ($result as $res) {
                    $data['name'] = $res->firstname.' '.$res->surname;
                    $data['profile'] = $res->profile;
                }
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]); 
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            $data = null;
        } finally {
            return $data;
        }
    }

    public function storePassword(&$params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $data = null;

        try {
            // $this->log([
            //     'Location' => __METHOD__.'(): 1',
            //     'params' => json_encode($params),
            // ]);

            // $data = $this->validate($params['formArray']);

            // if (null !== $data) {
            //     return $data;
            // }

            $userid = $this->encrypt->unscramble($params['formArray']['param'], 'userid');

            $password = ($params['formArray']['passkey'] === $params['formArray']['passkeychk']) ? $params['formArray']['passkey'] : null;

            // $this->log([
            //     'Location' => __METHOD__.'(): 2',
            //     'params' => json_encode($params),
            //     'userid' => $userid,
            //     'password' => ($params['formArray']['passkey'] === $params['formArray']['passkeychk']) ? $params['formArray']['passkey'] : 'null',
            // ]);

            if (null !== $password && -100 !== $userid) {
                $sql = ' update users
                            set passkey = ?
                          where userid = ?';

                $result = $this->db->query([
                    $this->encrypt->hashString($password),
                    $userid,
                ], $sql);

                $data = [
                    'rowcnt' => $this->db->getrowCount(),
                    'status' => $this->db->getStatus(),
                    'userid' => $userid,
                ];

                $sql = 'select email from users where userid = ?';

                $result = $this->db->query([
                    $userid,
                ], $sql);

                foreach ($result as $res) {
                    $data['email'] = $res->email;
                }

                $this->log([
                    'Location' => __METHOD__.'(): 3',
                    'params' => json_encode($params, JSON_PRETTY_PRINT),
                    'data' => json_encode($data, JSON_PRETTY_PRINT),
                ]);
            }
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            $data = null;
        } finally {
            return $data;
        }
    }

    public function callsCreate($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $calls = null;

        try {
            $calls['store'] = $this->encrypt->scramble('store', 'method');

            $calls['user'] = $this->encrypt->scramble('User\User', 'controller');

            switch (true) {
                case null === $calls['store']:
                    throw new Exception('Encryption failed of store');

                    break;

                case null === $calls['user']:
                    throw new Exception('Encryption failed of User');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'calls' => json_encode($calls),
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

    public function callsStore($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $calls = null;

        try {
            $calls['login'] = $this->encrypt->scramble('login', 'method');

            $calls['User'] = $this->encrypt->scramble('User\User', 'controller');

            $calls['calls_userid'] = $this->encrypt->scramble('-100', 'userid', $params['userid']);

            switch (true) {
                case null === $calls['login']:
                    throw new Exception('Encryption failed of login');

                    break;

                case null === $calls['User']:
                    throw new Exception('Encryption failed of User');

                    break;

                    case null === $calls['calls_userid']:
                        throw new Exception('Encryption failed of calls_userid');
    
            }

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'calls' => json_encode($calls),
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

    public function callsInviteStore($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $calls = null;

        try {
            $calls['inviteStore'] = $this->encrypt->scramble('inviteStore', 'method', $params['userid']);

            $calls['User'] = $this->encrypt->scramble('User\User', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['inviteStore']:
                    throw new Exception('Encryption failed of inviteStore');

                    break;

                case null === $calls['User']:
                    throw new Exception('Encryption failed of User');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'calls' => json_encode($calls),
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

    public function callsInviteStored($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $calls = null;

        try {
            $calls['confirm'] = $this->encrypt->scramble('confirm', 'method', $params['userid']);

            $calls['User'] = $this->encrypt->scramble('User\User', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['confirm']:
                    throw new Exception('Encryption failed of confirm');

                    break;

                case null === $calls['User']:
                    throw new Exception('Encryption failed of User');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'calls' => json_encode($calls),
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

    public function callsShow($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $calls = null;

        try {
            $calls['edit'] = $this->encrypt->scramble('edit', 'method');

            $calls['User'] = $this->encrypt->scramble('User\User', 'controller');

            switch (true) {
                case null === $calls['edit']:
                    throw new Exception('Encryption failed of edit');

                    break;

                case null === $calls['User']:
                    throw new Exception('Encryption failed of User');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'calls' => json_encode($calls),
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

    public function callsEdit($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $calls = null;

        try {
            $calls['update'] = $this->encrypt->scramble('update', 'method');

            $calls['User'] = $this->encrypt->scramble('User\User', 'controller');

            $calls['delete'] = $this->encrypt->scramble('delete', 'method');

            $calls['UserD'] = $this->encrypt->scramble('User\User', 'controller');

            switch (true) {
                case null === $calls['update']:
                    throw new Exception('Encryption failed of update');

                    break;

                case null === $calls['User']:
                    throw new Exception('Encryption failed of User');

                    break;

                case null === $calls['delete']:
                    throw new Exception('Encryption failed of delete');

                    break;

                case null === $calls['UserD']:
                    throw new Exception('Encryption failed of UserD');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'calls' => json_encode($calls),
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

    public function callsUpdate($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $calls = null;

        try {
            $calls['show'] = $this->encrypt->scramble('show', 'method');

            $calls['User'] = $this->encrypt->scramble('User\User', 'controller');

            switch (true) {
                case null === $calls['show']:
                    throw new Exception('Encryption failed of show');

                    break;

                case null === $calls['User']:
                    throw new Exception('Encryption failed of User');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'calls' => json_encode($calls),
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

    public function callsLoggedIn($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $calls = null;

        try {
            $calls['show'] = $this->encrypt->scramble('show', 'method');

            $calls['User1'] = $this->encrypt->scramble('User\User', 'controller');

            $calls['logout'] = $this->encrypt->scramble('logout', 'method');

            $calls['User2'] = $this->encrypt->scramble('User\User', 'controller');

            switch (true) {
                case null === $calls['show']:
                    throw new Exception('Encryption failed of show');

                    break;

                case null === $calls['User1']:
                    throw new Exception('Encryption failed of User1');

                    break;

                case null === $calls['logout']:
                    throw new Exception('Encryption failed of logout');

                    break;

                case null === $calls['User2']:
                    throw new Exception('Encryption failed of User2');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'calls' => json_encode($calls),
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

    public function callsResend($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $calls = null;

        try {
            $calls['forgot'] = $this->encrypt->scramble('forgot', 'method');

            $calls['User'] = $this->encrypt->scramble('User\User', 'controller');

            switch (true) {
                case null === $calls['forgot']:
                    throw new Exception('Encryption failed of forgot');

                    break;

                case null === $calls['User']:
                    throw new Exception('Encryption failed of User');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'calls' => json_encode($calls),
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

    public function callsforgot()
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $calls = null;

        try {
            $calls['updatePassword'] = $this->encrypt->scramble('updatePassword', 'method');

            $calls['User'] = $this->encrypt->scramble('User\User', 'controller');

            $calls['ParamNameUser'] = $this->encrypt->hashString('User');

            $calls['ParamNameconfirm'] = $this->encrypt->hashString('confirm');

            $calls['ParamNameconfirmation'] = $this->encrypt->hashString('confirmation');

            switch (true) {
                case null === $calls['updatePassword']:
                    throw new Exception('Encryption failed of updatePassword');

                    break;

                case null === $calls['User']:
                    throw new Exception('Encryption failed of User');

                    break;
                case null === $calls['ParamNameUser']:
                    throw new Exception('Hashing failed of ParamNameUser');

                    break;                    
                case null === $calls['ParamNameconfirm']:
                    throw new Exception('Hashing failed of ParamNameconfirm');

                    break;
                case null === $calls['ParamNameconfirmation']:
                    throw new Exception('Hashing failed of ParamNameconfirmation');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'calls' => json_encode($calls),
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

    public function callsupdatePassword($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $calls = null;

        try {
            $calls['storePassword'] = $this->encrypt->scramble('storePassword', 'method');

            $calls['User'] = $this->encrypt->scramble('User\User', 'controller');

            $calls['userid'] = $this->encrypt->scramble($params['userid'], 'userid');

            switch (true) {
                case null === $calls['storePassword']:
                    throw new Exception('Encryption failed of storePassword');

                    break;

                case null === $calls['User']:
                    throw new Exception('Encryption failed of User');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'calls' => json_encode($calls),
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

    public function callsstorePassword($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $calls = null;

        try {
            $calls['storePassword'] = $this->encrypt->scramble('storePassword', 'method');

            $calls['User'] = $this->encrypt->scramble('User\User', 'controller');

            $calls['userid'] = $this->encrypt->scramble($params['userid'], 'userid');

            switch (true) {
                case null === $calls['storePassword']:
                    throw new Exception('Encryption failed of storePassword');

                    break;

                case null === $calls['User']:
                    throw new Exception('Encryption failed of User');

                    break;

                case null === $calls['userid']:
                    throw new Exception('Encryption failed of userid');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'calls' => json_encode($calls),
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