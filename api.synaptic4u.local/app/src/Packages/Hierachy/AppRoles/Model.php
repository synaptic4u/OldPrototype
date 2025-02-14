<?php

namespace Synaptic4U\Packages\Hierachy\AppRoles;

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
            // ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
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
            if (sizeof($params['formArray']) > 0) {
                $params['formArray']['hierachyid'] = $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');

                if (null === $params['formArray']['hierachyid']) {
                    throw new Exception('Unable to decrypt HierachyId');
                }
            } else {
                throw new Exception('Form params do not meet the required length');
            }

            // $this->log([
            //     'Location' => __METHOD__ . '(): 0',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $sql = 'select count(*) as count from hierachy_applications where hierachyid = ?';

            $result = $this->db->query([
                $params['formArray']['hierachyid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['count'] = $res->count;
            }

            if ((int) $data['count'] > 0) {
                $sql = 'select ha.appid, ha.updatedon, a.name
                          from hierachy_applications ha
                          join applications a 
                            on ha.appid = a.appid
                         where ha.hierachyid = ?
                         order by a.name;';

                $result = $this->db->query([
                    $params['formArray']['hierachyid'],
                ], $sql, __METHOD__);

                foreach ($result as $key => $res) {
                    $data['apps'][$key] = [
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
                        'updatedon' => [
                            'pass' => 0,
                            'message' => null,
                            'value' => $res->updatedon,
                        ],
                    ];

                    if (null === $data['apps'][$key]['appid']['value']) {
                        throw new Exception('Could not encrypt '.$res->appid);
                    }
                }
            }

            $data['hierachyid']['value'] = $this->encrypt->scramble($params['formArray']['hierachyid'], 'hierachyid', $params['userid']);

            if (null === $data['hierachyid']['value']) {
                throw new Exception('Unable to encrypt hierachyId');
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

    public function getAppRoles($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $hierachyid = $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
                $appid = $this->encrypt->unscramble($params['formArray']['appid'], 'appid');

                if (null === $hierachyid) {
                    throw new Exception('Unable to decrypt HierachyId');
                }
                if (null === $appid) {
                    throw new Exception('Unable to decrypt appid');
                }
            } else {
                throw new Exception('Form params do not meet the required length');
            }

            $sql = 'select ha.appid, ha.updatedon as last_edited,
                           a.name as app,
                           am.moduleid, am.title as module, amm.title as parent_mod,
                           am.submoduleid, am.view, am.create, am.edit, am.delete
                      from hierachy_applications ha
                      join applications a 
                        on ha.appid = a.appid
                      left join application_modules am
                        on ha.appid = am.appid
                      left join application_modules amm
                        on am.submoduleid = amm.moduleid
                     where ha.appid = ?
                       and ha.hierachyid = ?
                     order by am.moduleid;';

            $result = $this->db->query([
                $appid,
                $hierachyid,
            ], $sql, __METHOD__);

            $data['app_roles']['app'] = $result[0]->app;
            $data['app_roles']['last_edited'] = $result[0]->last_edited;
            foreach ($result as $res) {
                $data['app_roles'][$res->moduleid] = [
                    'id' => $res->appid,
                    'modid' => $res->moduleid,
                    'submodid' => $res->submoduleid,
                    'appid' => [
                        'pass' => 0,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->appid, 'appid', $params['userid']),
                    ],
                    'moduleid' => [
                        'pass' => 0,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->moduleid, 'moduleid', $params['userid']),
                    ],
                    'module' => [
                        'pass' => 0,
                        'message' => null,
                        'value' => $res->module,
                    ],
                    'parent_module' => [
                        'pass' => 0,
                        'message' => null,
                        'value' => $res->parent_mod,
                    ],
                    'view' => [
                        'pass' => 0,
                        'message' => null,
                        'value' => $res->view,
                    ],
                    'create' => [
                        'pass' => 0,
                        'message' => null,
                        'value' => $res->create,
                    ],
                    'edit' => [
                        'pass' => 0,
                        'message' => null,
                        'value' => $res->edit,
                    ],
                    'delete' => [
                        'pass' => 0,
                        'message' => null,
                        'value' => $res->delete,
                    ],
                ];

                if (null === $data['app_roles'][$res->moduleid]['appid']['value']) {
                    throw new Exception('Could not encrypt '.$res->appid);
                }
                if (null === $data['app_roles'][$res->moduleid]['moduleid']['value']) {
                    throw new Exception('Could not encrypt '.$res->moduleid);
                }
            }

            $data['hierachyid']['value'] = $this->encrypt->scramble($hierachyid, 'hierachyid', $params['userid']);
            $data['appid']['value'] = $this->encrypt->scramble($appid, 'appid', $params['userid']);

            if (null === $data['hierachyid']['value']) {
                throw new Exception('Unable to encrypt hierachyId');
            }
            if (null === $data['appid']['value']) {
                throw new Exception('Unable to encrypt appid');
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'hierachyid' => $hierachyid,
                'appid' => $appid,
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

    public function edit($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;
        $module_list = [];

        try {
            if (sizeof($params['formArray']) > 1) {
                $hierachyid = $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
                $appid = $this->encrypt->unscramble($params['formArray']['appid'], 'appid');

                if (null === $hierachyid) {
                    throw new Exception('Unable to decrypt HierachyId');
                }
                if (null === $appid) {
                    throw new Exception('Unable to decrypt appid');
                }
            } else {
                throw new Exception('Form params do not meet the required length');
            }

            // $this->log([
            //     'Location' => __METHOD__ . '(): 0',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'hierachyid' => $hierachyid,
            //     'appid' => $appid,
            // ]);

            $sql = 'select a.name as app, max(hmr.updatedon) as app_last_edited
                      from hierachy_applications ha
                      join applications a 
                        on ha.appid = a.appid
                      left join application_modules am
                        on ha.appid = am.appid
                      left join ( select m.moduleid,
                                          case when isnull(max(r.updatedon)) then max(u.updatedon) 
                                                when isnull(max(u.updatedon)) then max(r.updatedon)
                                                when isnull(max(u.updatedon)) && isnull(max(r.updatedon)) then null 
                                               else case when max(r.updatedon) > max(u.updatedon) then max(r.updatedon) else max(u.updatedon) end end as updatedon
                                    from application_modules m
                                    left join hierachy_module_roles r
                                      on m.moduleid = r.moduleid
                                    left join hierachy_module_users u
                                      on m.moduleid = u.moduleid
                                   group by m.moduleid) hmr
                        on am.moduleid = hmr.moduleid
                     where ha.appid = ?
                       and ha.hierachyid = ?
                     group by a.name;';

            $result = $this->db->query([
                $appid,
                $hierachyid,
            ], $sql, __METHOD__);

            $data['app_roles']['app'] = $result[0]->app;
            $data['app_roles']['app_last_edited'] = $result[0]->app_last_edited;

            $sql = 'select ha.appid,
                           a.name as app,
                           am.moduleid, am.title as module, amm.title as parent_mod,
                           am.submoduleid, am.view, am.create, am.edit, am.delete,
                           hmr.updatedon as updatedon, concat(u.firstname, " ", u.surname) as maintainedby
                      from hierachy_applications ha
                      join applications a 
                        on ha.appid = a.appid
                      left join application_modules am
                        on ha.appid = am.appid
                      left join application_modules amm
                        on am.submoduleid = amm.moduleid
                      left join ( select m.moduleid,
                                    case when isnull(max(r.updatedon)) then max(u.updatedon) 
                                            when isnull(max(u.updatedon)) then max(r.updatedon)
                                            when isnull(max(u.updatedon)) && isnull(max(r.updatedon)) then null 
                                            else case when max(r.updatedon) > max(u.updatedon) then max(r.updatedon) else max(u.updatedon) end end as updatedon,
                                    case when isnull(max(r.updatedon)) then u.maintainedby
                                            when isnull(max(u.updatedon)) then r.maintainedby
                                            when isnull(max(u.updatedon)) && isnull(max(r.updatedon)) then null 
                                            else case when max(r.updatedon) > max(u.updatedon) then r.maintainedby else u.maintainedby end end as maintainedby
                                    from application_modules m
                                    left join hierachy_module_roles r
                                      on m.moduleid = r.moduleid
                                    left join hierachy_module_users u
                                      on m.moduleid = u.moduleid
                                   group by m.moduleid) hmr
                        on am.moduleid = hmr.moduleid
                      left join users u
                        on hmr.maintainedby = u.userid
                     where ha.appid = ?
                       and ha.hierachyid = ?
                     order by am.moduleid;';

            $result = $this->db->query([
                $appid,
                $hierachyid,
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['app_roles'][$res->moduleid] = [
                    'id' => $res->appid,
                    'modid' => $res->moduleid,
                    'submodid' => $res->submoduleid,
                    'appid' => [
                        'pass' => 0,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->appid, 'appid', $params['userid']),
                    ],
                    'moduleid' => [
                        'pass' => 0,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->moduleid, 'moduleid', $params['userid']),
                    ],
                    'module' => [
                        'pass' => 0,
                        'message' => null,
                        'value' => $res->module,
                    ],
                    'parent_module' => [
                        'pass' => 0,
                        'message' => null,
                        'value' => $res->parent_mod,
                    ],
                    'view' => [
                        'pass' => 0,
                        'message' => null,
                        'value' => $res->view,
                    ],
                    'create' => [
                        'pass' => 0,
                        'message' => null,
                        'value' => $res->create,
                    ],
                    'edit' => [
                        'pass' => 0,
                        'message' => null,
                        'value' => $res->edit,
                    ],
                    'delete' => [
                        'pass' => 0,
                        'message' => null,
                        'value' => $res->delete,
                    ],
                    'updatedon' => $res->updatedon,
                    'maintainedby' => $res->maintainedby,
                ];
                array_push($module_list, $res->moduleid);

                if (null === $data['app_roles'][$res->moduleid]['appid']['value']) {
                    throw new Exception('Could not encrypt '.$res->appid);
                }
                if (null === $data['app_roles'][$res->moduleid]['moduleid']['value']) {
                    throw new Exception('Could not encrypt '.$res->moduleid);
                }
            }

            array_shift($module_list);
            $moduleids = implode(',', $module_list);
            $data['module_list'] = $this->encrypt->scramble($moduleids, 'moduleids', $params['userid']);

            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'module_list' => json_encode($module_list, JSON_PRETTY_PRINT),
            //     'moduleids' => $moduleids,
            // ]);

            $data['hierachyid']['value'] = $this->encrypt->scramble($hierachyid, 'hierachyid', $params['userid']);
            $data['appid']['value'] = $this->encrypt->scramble($appid, 'appid', $params['userid']);

            if (null === $data['hierachyid']['value']) {
                throw new Exception('Unable to encrypt hierachyId');
            }
            if (null === $data['appid']['value']) {
                throw new Exception('Unable to encrypt appid');
            }

            $this->log([
                'Location' => __METHOD__.'() : 2',
                'hierachyid' => $hierachyid,
                'appid' => $appid,
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
                'module_list' => json_encode($module_list, JSON_PRETTY_PRINT),
                'moduleids' => $moduleids,
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

    public function editDetail($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $hierachyid = $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
                $appid = $this->encrypt->unscramble($params['formArray']['appid'], 'appid');
                $moduleid = $this->encrypt->unscramble($params['formArray']['moduleid'], 'moduleid');
                $moduleids = $this->encrypt->unscramble($params['formArray']['moduleids'], 'moduleids');
                $moduleids = ('000' === (string) $moduleids) ? [] : explode(',', $moduleids);

                if (null === $hierachyid) {
                    throw new Exception('Unable to decrypt HierachyId');
                }
                if (null === $appid) {
                    throw new Exception('Unable to decrypt appid');
                }
                if (null === $moduleid) {
                    throw new Exception('Unable to decrypt moduleid');
                }
                if (null === $moduleids) {
                    throw new Exception('Unable to decrypt moduleids');
                }
            } else {
                throw new Exception('Form params do not meet the required length');
            }

            $data['next_moduleid']['id'] = (sizeof($moduleids) > 0) ? array_shift($moduleids) : '000';
            $data['moduleid']['id'] = $moduleid;

            // $this->log([
            //     'Location' => __METHOD__ . '(): 0',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'hierachyid' => $hierachyid,
            //     'appid' => $appid,
            //     'moduleid' => $moduleid,
            //     'moduleids' => $moduleids,
            //     'next_moduleid' => $data['next_moduleid']['id'],
            // ]);

            $moduleid_list = (sizeof($moduleids) > 0) ? implode(',', $moduleids) : '000';
            $data['moduleids'] = $this->encrypt->scramble($moduleid_list, 'moduleids', $params['userid']);
            $data['hierachyid']['value'] = $this->encrypt->scramble($hierachyid, 'hierachyid', $params['userid']);
            $data['appid']['value'] = $this->encrypt->scramble($appid, 'appid', $params['userid']);
            $data['moduleid']['value'] = $this->encrypt->scramble($moduleid, 'moduleid', $params['userid']);
            $data['next_moduleid']['value'] = $this->encrypt->scramble($data['next_moduleid']['id'], 'moduleid', $params['userid']);

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

                case null === $data['next_moduleid']['value']:
                    throw new Exception('Encryption failed for: next_moduleid');

                    break;

                case null === $data['moduleids']:
                    throw new Exception('Encryption failed for: moduleids');

                    break;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
                'hierachyid' => $hierachyid,
                'appid' => $appid,
                'moduleid' => $moduleid,
                'moduleids' => $moduleids,
                'next_moduleid' => $data['next_moduleid']['id'],
                'moduleid_list' => $moduleid_list,
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

    public function showAppRoles($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $hierachyid = $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
                $appid = $this->encrypt->unscramble($params['formArray']['appid'], 'appid');
                $moduleid = $this->encrypt->unscramble($params['formArray']['moduleid'], 'moduleid');

                switch (true) {
                    case null === $hierachyid:
                        throw new Exception('Unable to decrypt: hierachyid');

                        break;

                    case null === $appid:
                        throw new Exception('Unable to decrypt: appid');

                        break;

                    case null === $moduleid:
                        throw new Exception('Unable to decrypt: moduleid');

                        break;
                }
            } else {
                throw new Exception('Form params do not meet the required length');
            }

            // $this->log([
            //     'Location' => __METHOD__ . '(): 0',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'hierachyid' => $hierachyid,
            //     'appid' => $appid,
            //     'moduleid' => $moduleid,
            // ]);

            $sql = 'select count(*) as count
                      from hierachy_module_roles
                     where hierachyid = ?
                       and moduleid = ?;';

            $result = $this->db->query([
                $hierachyid,
                $moduleid,
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['count'] = $res->count;
            }

            if ((int) $data['count'] > 0) {
                $sql = 'select hmr.hierachyid, hmr.roleid, hmr.moduleid, concat(u.firstname, " ", u.surname) as maintainedby, hmr.updatedon, r.name as role
                          from hierachy_module_roles hmr
                          left join hierachy_roles r
                            on hmr.roleid = r.roleid
                          left join users u
                            on hmr.maintainedby = u.userid
                         where hmr.hierachyid = ?
                           and hmr.moduleid = ?;';

                $result = $this->db->query([
                    $hierachyid,
                    $moduleid,
                ], $sql, __METHOD__);

                foreach ($result as $res) {
                    $data['mod_roles'][$res->roleid] = [
                        'id' => $res->roleid,
                        'modid' => $res->moduleid,
                        'appid' => $appid,
                        'roleid' => [
                            'pass' => 0,
                            'message' => null,
                            'value' => $this->encrypt->scramble($res->roleid, 'roleid', $params['userid']),
                        ],
                        'role' => [
                            'pass' => 0,
                            'message' => null,
                            'value' => $res->role,
                        ],
                        'updatedon' => [
                            'pass' => 0,
                            'message' => null,
                            'value' => $res->updatedon,
                        ],
                        'maintainedby' => [
                            'pass' => 0,
                            'message' => null,
                            'value' => $res->maintainedby,
                        ],
                    ];

                    if (null === $data['mod_roles'][$res->roleid]['roleid']['value']) {
                        throw new Exception('Could not encrypt roleid :'.$res->roleid);
                    }
                }
            }

            $data['moduleid']['id'] = $moduleid;

            $data['hierachyid']['value'] = $this->encrypt->scramble($hierachyid, 'hierachyid', $params['userid']);
            $data['appid']['value'] = $this->encrypt->scramble($appid, 'appid', $params['userid']);
            $data['moduleid']['value'] = $this->encrypt->scramble($moduleid, 'moduleid', $params['userid']);

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
                'hierachyid' => $hierachyid,
                'appid' => $appid,
                'moduleid' => $moduleid,
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

    public function storeAppRoles($params)
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
                $params['formArray']['roleid'] = $this->encrypt->unscramble($params['formArray']['roleid'], 'roleid');

                switch (true) {
                    case null === $params['formArray']['hierachyid']:
                        throw new Exception('Unable to decrypt: hierachyid');

                        break;

                    case null === $params['formArray']['appid']:
                        throw new Exception('Unable to decrypt: appid');

                        break;

                    case null === $params['formArray']['moduleid']:
                        throw new Exception('Unable to decrypt: moduleid');

                        break;

                    case null === $params['formArray']['roleid']:
                        throw new Exception('Unable to decrypt: roleid');

                        break;
                }
            } else {
                throw new Exception('Form params do not meet the required length');
            }

            $data = $this->validate->isValid('AppRoles', $params['formArray']);
            $data['roleid']['id'] = $data['roleid']['value'];
            $data['moduleid']['id'] = $data['moduleid']['value'];

            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'data' => json_encode($data, JSON_PRETTY_PRINT),
            // ]);

            if ($data['return'] > 0) {
                return $data;
            }

            $sql = 'select count(*) as count 
                      from hierachy_module_roles 
                     where hierachyid = ?
                       and moduleid = ?
                       and roleid = ?;';

            $result = $this->db->query([
                $data['hierachyid']['value'],
                $data['moduleid']['value'],
                $data['roleid']['value'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['count'] = $res->count;
            }

            if (0 === (int) $data['count']) {
                $sql = 'insert into hierachy_module_roles(hierachyid, roleid, moduleid, maintainedby)
                        values(?, ?, ?, ?);';

                $result = $this->db->query([
                    $data['hierachyid']['value'],
                    $data['roleid']['value'],
                    $data['moduleid']['value'],
                    $params['userid'],
                ], $sql, __METHOD__);

                $data['rowcount'] = $this->db->getrowCount();
            }

            $data['hierachyid']['value'] = $this->encrypt->scramble($data['hierachyid']['value'], 'hierachyid', $params['userid']);
            $data['appid']['value'] = $this->encrypt->scramble($data['appid']['value'], 'appid', $params['userid']);
            $data['roleid']['value'] = $this->encrypt->scramble($data['roleid']['value'], 'roleid', $params['userid']);
            $data['moduleid']['value'] = $this->encrypt->scramble($data['moduleid']['value'], 'moduleid', $params['userid']);

            switch (true) {
                case null === $data['hierachyid']['value']:
                    throw new Exception('Unable to decrypt: hierachyid');

                    break;

                case null === $data['appid']['value']:
                    throw new Exception('Unable to decrypt: appid');

                    break;

                case null === $data['moduleid']['value']:
                    throw new Exception('Unable to decrypt: moduleid');

                    break;

                case null === $data['roleid']['value']:
                    throw new Exception('Unable to decrypt: roleid');

                    break;
            }

            $this->log([
                'Location' => __METHOD__.'(): 2',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);

            if (null === $data) {
                throw new Exception('Could not add Applications');
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

    public function removeAppRoles($params)
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
                $params['formArray']['roleid'] = $this->encrypt->unscramble($params['formArray']['roleid'], 'roleid');

                switch (true) {
                    case null === $params['formArray']['hierachyid']:
                        throw new Exception('Unable to decrypt: hierachyid');

                        break;

                    case null === $params['formArray']['appid']:
                        throw new Exception('Unable to decrypt: appid');

                        break;

                    case null === $params['formArray']['moduleid']:
                        throw new Exception('Unable to decrypt: moduleid');

                        break;

                    case null === $params['formArray']['roleid']:
                        throw new Exception('Unable to decrypt: roleid');

                        break;
                }
            } else {
                throw new Exception('Form params do not meet the required length');
            }

            $data = $this->validate->isValid('AppRoles', $params['formArray']);
            $data['roleid']['id'] = $data['roleid']['value'];
            $data['moduleid']['id'] = $data['moduleid']['value'];

            // $this->log([
            //     'Location' => __METHOD__ . '() : 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'data' => json_encode($data, JSON_PRETTY_PRINT),
            // ]);

            if ($data['return'] > 0) {
                return $data;
            }

            $sql = 'select count(*) as count 
                      from hierachy_module_roles 
                     where hierachyid = ?
                       and moduleid = ?
                       and roleid = ?;';

            $result = $this->db->query([
                $data['hierachyid']['value'],
                $data['moduleid']['value'],
                $data['roleid']['value'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['count'] = $res->count;
            }

            if ((int) $data['count'] > 0) {
                $sql = 'delete 
                          from hierachy_module_roles
                         where hierachyid = ?
                           and roleid = ?
                           and moduleid = ?;';

                $result = $this->db->query([
                    $data['hierachyid']['value'],
                    $data['roleid']['value'],
                    $data['moduleid']['value'],
                ], $sql, __METHOD__);

                $data['rowcount'] = $this->db->getrowCount();
            }

            $data['hierachyid']['value'] = $this->encrypt->scramble($data['hierachyid']['value'], 'hierachyid', $params['userid']);
            $data['appid']['value'] = $this->encrypt->scramble($data['appid']['value'], 'appid', $params['userid']);
            $data['roleid']['value'] = $this->encrypt->scramble($data['roleid']['value'], 'roleid', $params['userid']);
            $data['moduleid']['value'] = $this->encrypt->scramble($data['moduleid']['value'], 'moduleid', $params['userid']);

            switch (true) {
                case null === $data['hierachyid']['value']:
                    throw new Exception('Unable to decrypt: hierachyid');

                    break;

                case null === $data['appid']['value']:
                    throw new Exception('Unable to decrypt: appid');

                    break;

                case null === $data['moduleid']['value']:
                    throw new Exception('Unable to decrypt: moduleid');

                    break;

                case null === $data['roleid']['value']:
                    throw new Exception('Unable to decrypt: roleid');

                    break;
            }

            $this->log([
                'Location' => __METHOD__.'(): 2',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);

            if (null === $data) {
                throw new Exception('Could not add Applications');
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

    public function callsShow($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['approles'] = $this->encrypt->scramble('Hierachy\AppRoles\AppRoles', 'controller', $params['userid']);
            $calls['edit'] = $this->encrypt->scramble('edit', 'method', $params['userid']);

            switch (true) {
                case null === $calls['approles']:
                    throw new Exception('Encryption failed of controller: approles');

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

    public function callsEdit($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['approles'] = $this->encrypt->scramble('Hierachy\AppRoles\AppRoles', 'controller', $params['userid']);
            $calls['editDetail'] = $this->encrypt->scramble('editDetail', 'method', $params['userid']);

            switch (true) {
                case null === $calls['approles']:
                    throw new Exception('Encryption failed of controller: approles');

                    break;

                case null === $calls['editDetail']:
                    throw new Exception('Encryption failed of method: editDetail');

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

    public function callsShowAppRoles($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['approles'] = $this->encrypt->scramble('Hierachy\AppRoles\AppRoles', 'controller', $params['userid']);
            $calls['storeAppRoles'] = $this->encrypt->scramble('storeAppRoles', 'method', $params['userid']);
            $calls['removeAppRoles'] = $this->encrypt->scramble('removeAppRoles', 'method', $params['userid']);

            switch (true) {
                case null === $calls['approles']:
                    throw new Exception('Encryption failed of controller: approles');

                    break;

                case null === $calls['storeAppRoles']:
                    throw new Exception('Encryption failed of method: storeAppRoles');

                    break;

                case null === $calls['removeAppRoles']:
                    throw new Exception('Encryption failed of method: removeAppRoles');

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

    public function callsEditDetail($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['approles'] = $this->encrypt->scramble('Hierachy\AppRoles\AppRoles', 'controller', $params['userid']);
            $calls['editDetail'] = $this->encrypt->scramble('editDetail', 'method', $params['userid']);

            switch (true) {
                case null === $calls['approles']:
                    throw new Exception('Encryption failed of controller: approles');

                    break;

                case null === $calls['editDetail']:
                    throw new Exception('Encryption failed of method: editDetail');

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

            $calls['approles'] = $this->encrypt->scramble('Hierachy\AppRoles\AppRoles', 'controller', $params['userid']);
            $calls['showAppRoles'] = $this->encrypt->scramble('showAppRoles', 'method', $params['userid']);

            switch (true) {
                case null === $calls['approles']:
                    throw new Exception('Encryption failed of controller: approles');

                    break;

                case null === $calls['showAppRoles']:
                    throw new Exception('Encryption failed of method: showAppRoles');

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

    public function callsAppRolesRemoved($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['approles'] = $this->encrypt->scramble('Hierachy\AppRoles\AppRoles', 'controller', $params['userid']);
            $calls['showAppRoles'] = $this->encrypt->scramble('showAppRoles', 'method', $params['userid']);

            switch (true) {
                case null === $calls['approles']:
                    throw new Exception('Encryption failed of controller: approles');

                    break;

                case null === $calls['showAppRoles']:
                    throw new Exception('Encryption failed of method: showAppRoles');

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
        new Log($msg, 'activity');
    }
}
