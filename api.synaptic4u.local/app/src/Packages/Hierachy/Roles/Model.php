<?php

namespace Synaptic4U\Packages\Hierachy\Roles;

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

    public function show($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $params['formArray']['hierachyid'] = (isset($params['formArray']['hierachyid'])) ? $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid') : $this->encrypt->unscramble($params['formArray'][0], 'hierachyid');
                $params['formArray']['detid'] = (isset($params['formArray']['detid'])) ? $this->encrypt->unscramble($params['formArray']['detid'], 'detid') : $this->encrypt->unscramble($params['formArray'][1], 'detid');

                $data['hierachyid'] = [
                    'pass' => null,
                    'message' => null,
                    'value' => $this->encrypt->scramble($params['formArray']['hierachyid'], 'hierachyid', $params['userid']),
                ];
                $data['detid'] = [
                    'pass' => null,
                    'message' => null,
                    'value' => $this->encrypt->scramble($params['formArray']['detid'], 'detid', $params['userid']),
                ];

                if (null === $params['formArray']['hierachyid']) {
                    throw new Exception('Unable to decrypt hierachyid');
                }
                if (null === $params['formArray']['detid']) {
                    throw new Exception('Unable to decrypt detid');
                }
                if (null === $data['hierachyid']['value']) {
                    throw new Exception('Unable to encrypt hierachyid');
                }
                if (null === $data['detid']['value']) {
                    throw new Exception('Unable to encrypt detid');
                }
            } else {
                throw new Exception('FormArray is too short');
            }
            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'data' => json_encode($data, JSON_PRETTY_PRINT),
            // ]);

            $sql = 'call GetHierachyRoles(?);';

            $result = $this->db->callProc([
                $params['formArray']['hierachyid'],
            ], $sql, __METHOD__);

            foreach ($result as $key => $res) {
                $data['roles'][$key] = [
                    'exclude' => $res->exclude,
                    'default' => $res->hierachyid,
                    'userid' => $res->userid,
                    'hierachyid' => [
                        'id' => $res->hierachyid,
                        'pass' => null,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->hierachyid, 'hierachyid', $params['userid']),
                    ],
                    'roleid' => [
                        'id' => $res->roleid,
                        'pass' => null,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->roleid, 'roleid', $params['userid']),
                    ],
                    'role' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->role,
                    ],
                    'datedon' => $res->updatedon,
                    'user' => $res->user,
                    'create' => $res->create,
                    'view' => $res->view,
                    'edit' => $res->edit,
                    'delete' => $res->delete,
                ];

                if (null === $data['roles'][$key]['roleid']['value']) {
                    throw new Exception('Unable to encrypt '.$res->role.' : '.$res->roleid.' roleid.');
                }
                if (null === $data['roles'][$key]['hierachyid']['value']) {
                    throw new Exception('Unable to encrypt '.$res->role.' : '.$res->hierachyid.' hierachyid.');
                }
            }

            $this->log([
                'Location' => __METHOD__.'() : 2',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
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
                throw new Exception('FormArray is too short');
            }

            $params['formArray']['roleid'] = 0;
            $params['formArray']['exclude'] = 0;
            $params['formArray']['view'] = ('on' === (string) $params['formArray']['view']) ? 1 : 0;
            $params['formArray']['edit'] = ('on' === (string) $params['formArray']['edit']) ? 1 : 0;
            $params['formArray']['create'] = ('on' === (string) $params['formArray']['create']) ? 1 : 0;
            $params['formArray']['delete'] = ('on' === (string) $params['formArray']['delete']) ? 1 : 0;
            $params['formArray']['userid'] = $params['userid'];

            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $data = $this->validate->isValid('Roles', $params['formArray']);

            // $this->log([
            //     'Location' => __METHOD__ . '() : 2',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'data' => json_encode($data, JSON_PRETTY_PRINT),
            // ]);

            if (0 === (int) $data['return']) {
                $sql = 'insert into hierachy_roles(userid, name, hierachyid, exclude, `create`, `view`, `edit`, `delete`)values(?, ?, ?, ?, ?, ?, ?, ?);';

                $result = $this->db->query([
                    $params['userid'],
                    $data['role']['value'],
                    $data['hierachyid']['value'],
                    $data['exclude']['value'],
                    $data['create']['value'],
                    $data['view']['value'],
                    $data['edit']['value'],
                    $data['delete']['value'],
                ], $sql, __METHOD__);

                $data['roleid']['value'] = $this->db->getLastId();
            }

            $data['hierachyid']['value'] = $this->encrypt->scramble($params['formArray']['hierachyid'], 'hierachyid', $params['userid']);
            $data['detid']['value'] = $this->encrypt->scramble($params['formArray']['detid'], 'detid', $params['userid']);

            if (null === $data['hierachyid']['value']) {
                throw new Exception('Unable to encypt hierachyid');
            }
            if (null === $data['detid']['value']) {
                throw new Exception('Unable to encypt detid');
            }

            $this->log([
                'Location' => __METHOD__.'() : 3',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
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
                throw new Exception('FormArray is too short');
            }

            $this->log([
                'Location' => __METHOD__.'() : 2',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
            ]);

            $sql = 'select hr.roleid, concat(u.firstname, " ", u.surname) as user, hr.name as role, hr.updatedon, 
                           hr.exclude, hr.create, hr.view, hr.edit, hr.delete
                      from hierachy_roles hr
                      join users u
                        on hr.maintainedby = u.userid
                     where hr.roleid = ?;';

            $result = $this->db->query([
                $params['formArray']['roleid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['role'] = [
                    'roleid' => [
                        'pass' => null,
                        'value' => $this->encrypt->scramble($res->roleid, 'roleid', $params['userid']),
                        'message' => null,
                    ],
                    'role' => [
                        'pass' => null,
                        'value' => $res->role,
                        'message' => null,
                    ],
                    'exclude' => [
                        'pass' => null,
                        'value' => $res->exclude,
                        'message' => null,
                    ],
                    'user' => [
                        'pass' => null,
                        'value' => $res->user,
                        'message' => null,
                    ],
                    'updatedon' => [
                        'pass' => null,
                        'value' => $res->updatedon,
                        'message' => null,
                    ],
                    'create' => [
                        'pass' => null,
                        'value' => $res->create,
                        'message' => null,
                    ],
                    'view' => [
                        'pass' => null,
                        'value' => $res->view,
                        'message' => null,
                    ],
                    'edit' => [
                        'pass' => null,
                        'value' => $res->edit,
                        'message' => null,
                    ],
                    'delete' => [
                        'pass' => null,
                        'value' => $res->delete,
                        'message' => null,
                    ],
                ];

                if (null === $data['role']['roleid']['value']) {
                    throw new Exception('Unable to encypt roleid');
                }
            }

            $data['hierachyid']['value'] = $this->encrypt->scramble($params['formArray']['hierachyid'], 'hierachyid', $params['userid']);
            $data['detid']['value'] = $this->encrypt->scramble($params['formArray']['detid'], 'detid', $params['userid']);

            if (null === $data['hierachyid']['value']) {
                throw new Exception('Unable to encypt hierachyid');
            }
            if (null === $data['detid']['value']) {
                throw new Exception('Unable to encypt detid');
            }

            $this->log([
                'Location' => __METHOD__.'() : 3',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
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
                if (null === $params['formArray']['roleid']) {
                    throw new Exception('Unable to decrypt roleid');
                }
            } else {
                throw new Exception('FormArray is too short');
            }

            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $params['formArray']['exclude'] = ('on' === (string) $params['formArray']['exclude']) ? 0 : 1;
            $params['formArray']['create'] = ('on' === (string) $params['formArray']['create']) ? 1 : 0;
            $params['formArray']['view'] = ('on' === (string) $params['formArray']['view']) ? 1 : 0;
            $params['formArray']['edit'] = ('on' === (string) $params['formArray']['edit']) ? 1 : 0;
            $params['formArray']['delete'] = ('on' === (string) $params['formArray']['delete']) ? 1 : 0;
            $params['formArray']['userid'] = $params['userid'];

            $data = $this->validate->isValid('Roles', $params['formArray']);

            // $this->log([
            //     'Location' => __METHOD__ . '() : 2',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'data' => json_encode($data, JSON_PRETTY_PRINT),
            // ]);

            if (0 === (int) $data['return']) {
                // $this->log([
                //     'Location' => __METHOD__ . '() : 2.5',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                // ]);

                $sql = 'update hierachy_roles
                           set userid = ?,
                               name = ?,
                               exclude = ?,
                               updatedon = now(),
                               `create` = ?,
                               `view` = ?,
                               `edit` = ?,
                               `delete` = ?
                         where roleid = ?;';

                $result = $this->db->query([
                    $params['userid'],
                    $params['formArray']['role'],
                    $params['formArray']['exclude'],
                    $params['formArray']['create'],
                    $params['formArray']['view'],
                    $params['formArray']['edit'],
                    $params['formArray']['delete'],
                    $params['formArray']['roleid'],
                ], $sql, __METHOD__);

                $data['rowcount'] = $this->db->getrowCount();

                $data['status'] = $this->db->getStatus();
            }

            $data['hierachyid']['value'] = $this->encrypt->scramble($params['formArray']['hierachyid'], 'hierachyid', $params['userid']);
            $data['detid']['value'] = $this->encrypt->scramble($params['formArray']['detid'], 'detid', $params['userid']);
            $data['roleid']['value'] = $this->encrypt->scramble($params['formArray']['roleid'], 'roleid', $params['userid']);

            if (null === $data['hierachyid']['value']) {
                throw new Exception('Unable to encypt hierachyid');
            }
            if (null === $data['detid']['value']) {
                throw new Exception('Unable to encypt detid');
            }
            if (null === $data['roleid']['value']) {
                throw new Exception('Unable to encypt roleid');
            }

            $this->log([
                'Location' => __METHOD__.'() : 3',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
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
                throw new Exception('FormArray is too short');
            }

            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $sql = 'select count(*) as count, 
                           case when isnull(name) then null else name end as role 
                      from hierachy_roles 
                     where hierachyid = ? 
                       and roleid = ?;';

            $result = $this->db->query([
                $params['formArray']['hierachyid'],
                $params['formArray']['roleid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['role'] = $res->role;
                $data['count'] = $res->count;
            }

            if ((int) $data['count'] > 0) {
            }

            $sql = 'call GetHierachyRoleDependants(?, ?);';

            $result = $this->db->callProc([
                $params['formArray']['hierachyid'],
                $params['formArray']['roleid'],
            ], $sql, __METHOD__);

            $data['dependant']['count'] = $this->db->getrowCount();

            foreach ($result as $key => $res) {
                $data['orgs'][$key] = [
                    'name' => $res->org,
                ];
            }

            if (0 === (int) $data['count']) {
                $sql = 'delete from hierachy_roles where hierachyid = ? and roleid = ?;';

                $result = $this->db->query([
                    $params['formArray']['hierachyid'],
                    $params['formArray']['roleid'],
                ], $sql, __METHOD__);

                $data['rowcount'] = $this->db->getrowCount();
            }

            $data['hierachyid']['value'] = $this->encrypt->scramble($params['formArray']['hierachyid'], 'hierachyid', $params['userid']);
            $data['detid']['value'] = $this->encrypt->scramble($params['formArray']['detid'], 'detid', $params['userid']);
            $data['roleid']['value'] = $this->encrypt->scramble($params['formArray']['roleid'], 'roleid', $params['userid']);

            if (null === $data['hierachyid']['value']) {
                throw new Exception('Unable to encypt hierachyid');
            }
            if (null === $data['detid']['value']) {
                throw new Exception('Unable to encypt detid');
            }
            if (null === $data['roleid']['value']) {
                throw new Exception('Unable to encypt roleid');
            }

            $this->log([
                'Location' => __METHOD__.'() : 3',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
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

    public function toggle($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $params['formArray']['hierachyid'] = $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
                $params['formArray']['roleid'] = $this->encrypt->unscramble($params['formArray']['roleid'], 'roleid');

                if (null === $params['formArray']['hierachyid']) {
                    throw new Exception('Unable to decrypt hierachyid');
                }
                if (null === $params['formArray']['roleid']) {
                    throw new Exception('Unable to decrypt roleid');
                }
            } else {
                throw new Exception('FormArray is too short');
            }

            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $sql = 'call GetHierachyRoleDependants(?, ?);';

            $result = $this->db->callProc([
                $params['formArray']['hierachyid'],
                $params['formArray']['roleid'],
            ], $sql, __METHOD__);

            $data['count'] = $this->db->getrowCount();

            if (0 === (int) $data['count']) {
                $sql = 'update hierachy_roles
                           set exclude = ?, updatedon = now() 
                         where roleid = ?;';

                $result = $this->db->query([
                    (0 === (int) $params['formArray']['exclude']) ? 1 : 0,
                    $params['formArray']['roleid'],
                ], $sql, __METHOD__);

                $data['rowcount'] = $this->db->getrowCount();
            } else {
                $sql = 'call GetHierachyRoleDependants(?, ?);';

                $result = $this->db->callProc([
                    $params['formArray']['hierachyid'],
                    $params['formArray']['roleid'],
                ], $sql, __METHOD__);

                foreach ($result as $key => $res) {
                    $data['orgs'][$key] = [
                        'name' => $res->org,
                        'user' => $res->user,
                    ];
                }
            }

            $sql = 'select hr.roleid, hr.userid, hr.name as role, hr.updatedon, hr.hierachyid, hr.exclude, case when hr.userid=3 then "System Default" else concat(u.firstname, " ", u.surname) end as user
                      from hierachy_roles hr
                      join users u
                        on hr.userid = u.userid
                     where hr.roleid = ?;';

            $result = $this->db->query([
                $params['formArray']['roleid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['userid'] = $res->userid;
                $data['user'] = $res->user;
                $data['role']['value'] = $res->role;
                $data['datedon'] = $res->updatedon;
                $data['exclude'] = $res->exclude;
                $data['hierachyid']['value'] = $this->encrypt->scramble((0 === (int) $res->hierachyid) ? '000' : $res->hierachyid, 'hierachyid', $params['userid']);
                $data['roleid']['value'] = $this->encrypt->scramble($res->roleid, 'roleid', $params['userid']);

                if (null === $data['hierachyid']['value']) {
                    throw new Exception('Unable to encypt hierachyid');
                }
                if (null === $data['roleid']['value']) {
                    throw new Exception('Unable to encypt roleid');
                }
            }

            $this->log([
                'Location' => __METHOD__.'() : 3',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
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

    public function getRoleName($roleid)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            $sql = 'select name from hierachy_roles where roleid = ?;';

            $result = $this->db->query([
                $roleid,
            ], $sql, __METHOD__);

            foreach ($result as $key => $res) {
                $data = $res->name;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'roleid' => $roleid,
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

    public function getRoles($params, $roleid, $roleids, $exclude)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;
        $hierachyid = null;
        $detid = null;

        try {
            if (sizeof($params['formArray']) > 0) {
                $hierachyid = (isset($params['formArray']['hierachyid'])) ? $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid') : $this->encrypt->unscramble($params['formArray'][0], 'hierachyid');

                if (null === $hierachyid) {
                    throw new Exception('Unable to decrypt hierachyid');
                }
            } else {
                throw new Exception('FormArray is too short');
            }
            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'data' => json_encode($data, JSON_PRETTY_PRINT),
            //     'hierachyid' => $hierachyid
            // ]);

            $sql = 'call GetHierachyRoles(?);';

            $result = $this->db->callProc([
                $hierachyid,
            ], $sql, __METHOD__);

            foreach ($result as $key => $res) {
                if (0 == (int) $res->exclude) {
                    if (1 === (int) $exclude) {
                        if (!in_array((int) $res->roleid, $roleids)) {
                            $data[$key] = [
                                'roleid' => [
                                    'id' => $res->roleid,
                                    'selected' => (isset($roleid['id'])) ? (((int) $roleid['id'] === (int) $res->roleid) ? 1 : 0) : (((int) $roleid === (int) $res->roleid) ? 1 : 0),
                                    'pass' => null,
                                    'message' => null,
                                    'value' => $this->encrypt->scramble($res->roleid, 'roleid', $params['userid']),
                                ],
                                'role' => [
                                    'pass' => null,
                                    'message' => null,
                                    'value' => $res->role,
                                ],
                                'view' => (1 === (int) $res->view) ? 'V' : null,
                                'create' => (1 === (int) $res->create) ? 'C' : null,
                                'edit' => (1 === (int) $res->edit) ? 'E' : null,
                                'delete' => (1 === (int) $res->delete) ? 'D' : null,
                            ];

                            if (null === $data[$key]['roleid']['value']) {
                                throw new Exception('Unable to encrypt '.$res->role.' : '.$res->roleid.' roleid.');
                            }
                        }
                    } else {
                        $data[$key] = [
                            'roleid' => [
                                'id' => $res->roleid,
                                'selected' => ((int) $roleid === (int) $res->roleid) ? 1 : 0,
                                'pass' => null,
                                'message' => null,
                                'value' => $this->encrypt->scramble($res->roleid, 'roleid', $params['userid']),
                            ],
                            'role' => [
                                'pass' => null,
                                'message' => null,
                                'value' => $res->role,
                            ],
                            'view' => (1 === (int) $res->view) ? 'V' : null,
                            'create' => (1 === (int) $res->create) ? 'C' : null,
                            'edit' => (1 === (int) $res->edit) ? 'E' : null,
                            'delete' => (1 === (int) $res->delete) ? 'D' : null,
                        ];

                        if (null === $data[$key]['roleid']['value']) {
                            throw new Exception('Unable to encrypt '.$res->role.' : '.$res->roleid.' roleid.');
                        }
                    }
                }
            }

            $this->log([
                'Location' => __METHOD__.'() : 2',
                'roleid' => $roleid,
                'hierachyid' => $hierachyid,
                'params' => json_encode($params, JSON_PRETTY_PRINT),
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

    public function callsToggle($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['roles'] = $this->encrypt->scramble('Hierachy\Roles\Roles', 'controller', $params['userid']);
            $calls['toggle'] = $this->encrypt->scramble('toggle', 'method', $params['userid']);
            $calls['edit'] = $this->encrypt->scramble('edit', 'method', $params['userid']);

            switch (true) {
                case null === $calls['roles']:
                    throw new Exception('Encryption failed of controller: roles');

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
                'Location' => __METHOD__.'()',
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

            $calls['users'] = $this->encrypt->scramble('Hierachy\Users\Users', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);
            $calls['roles'] = $this->encrypt->scramble('Hierachy\Roles\Roles', 'controller', $params['userid']);
            $calls['edit'] = $this->encrypt->scramble('edit', 'method', $params['userid']);
            $calls['store'] = $this->encrypt->scramble('store', 'method', $params['userid']);
            $calls['toggle'] = $this->encrypt->scramble('toggle', 'method', $params['userid']);

            switch (true) {
                case null === $calls['users']:
                    throw new Exception('Encryption failed of controller: users');

                    break;

                case null === $calls['show']:
                    throw new Exception('Encryption failed of method: show');

                    break;

                    case null === $calls['roles']:
                        throw new Exception('Encryption failed of controller: roles');

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

    public function callsEdit($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['roles'] = $this->encrypt->scramble('Hierachy\Roles\Roles', 'controller', $params['userid']);
            $calls['update'] = $this->encrypt->scramble('update', 'method', $params['userid']);
            $calls['delete'] = $this->encrypt->scramble('delete', 'method', $params['userid']);

            switch (true) {
                case null === $calls['roles']:
                    throw new Exception('Encryption failed of controller: roles');

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
                'Location' => __METHOD__.'()',
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

            $calls['roles'] = $this->encrypt->scramble('Hierachy\Roles\Roles', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['roles']:
                    throw new Exception('Encryption failed of controller: roles');

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

    public function callsDeleted($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['roles'] = $this->encrypt->scramble('Hierachy\Roles\Roles', 'controller', $params['userid']);
            $calls['edit'] = $this->encrypt->scramble('edit', 'method', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);
            $calls['toggle'] = $this->encrypt->scramble('toggle', 'method', $params['userid']);

            switch (true) {
                case null === $calls['roles']:
                    throw new Exception('Encryption failed of controller: roles');

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
                'Location' => __METHOD__.'()',
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

            $calls['roles'] = $this->encrypt->scramble('Hierachy\Roles\Roles', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['roles']:
                    throw new Exception('Encryption failed of controller: roles');

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
