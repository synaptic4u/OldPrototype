<?php

namespace Synaptic4U\Packages\Applications;

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

            // $this->log([
            //     'Location' => __METHOD__.'()',
            // ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            return null;
        }
    }

    public function listApps($params, $id = null)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $data = [];

            $sql = 'select appid, name as app, description from applications where 1 = ? order by name;';

            $result = $this->db->query([
                1,
            ], $sql, __METHOD__);

            $data['headings'] = [
                'name' => 'appid',
                'id' => 'SelectApplications',
                'legend' => 'Available Applications',
                'required' => 'required',
                'invalid_msg' => 'A selection is required.',
            ];

            foreach ($result as $res) {
                $data['select'][] = [
                    'id' => $this->encrypt->scramble($res->appid, 'appid', $params['userid']),
                    'appid' => $res->appid,
                    'name' => [
                        'pass' => 0,
                        'message' => null,
                        'value' => $res->app,
                        'selected' => ((int) $id === (int) $res->appid) ? 1 : 0,
                    ],
                    'description' => [
                        'pass' => 0,
                        'message' => null,
                        'value' => $res->description,
                    ],
                ];
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

    public function loadPersonnelApps($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $data = [];

            $data = $this->checkPersonnelApps($params['userid']);

            if ($data['count'] > 0) {
                $this->log([
                    'Location' => __METHOD__.'() : 1',
                    'params' => json_encode($params, JSON_PRETTY_PRINT),
                    'data' => json_encode($data, JSON_PRETTY_PRINT),
                ]);

                $data['apps'] = $this->getPersonnelApps($params['userid']);

                if (sizeof($data) > 0) {
                    foreach ($data['apps'] as $key => $app) {
                        $sql = 'select m.moduleid, m.title, m.controller, m.method, 
                                       case when isnull(m.submoduleid) then m.moduleid else m.submoduleid end as submoduleid
                                  from application_users u
                                  join application_modules m
                                    on u.appid = m.appid
                                 where u.appid = ?
                                   and u.userid = ?
                                 order by submoduleid, m.moduleid;';
                        $result = $this->db->query([
                            $key,
                            $params['userid'],
                        ], $sql, __METHOD__);

                        foreach ($result as $res) {
                            $data['data'][$key]['menu'][$res->moduleid] = [
                                'moduleid' => $res->moduleid,
                                'title' => $res->title,
                                'controller' => $res->controller,
                                'method' => $res->method,
                                'submoduleid' => $res->submoduleid,
                            ];
                        }
                    }

                    $data['data'] = $this->callsLoadApps($params, $data);

                    $this->log([
                        'Location' => __METHOD__.'() : 2',
                        'params' => json_encode($params, JSON_PRETTY_PRINT),
                        'data' => json_encode($data, JSON_PRETTY_PRINT),
                    ]);
                }
            }

            $this->log([
                'Location' => __METHOD__.'() : 3',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);

            if (null === $data) {
                throw new Exception('Unable to query application_users');
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

    public function loadOrgApps($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        // $this->log([
        //     'Location' => __METHOD__.'() : 0',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        // ]);

        try {
            $data = [];

            $data = $this->checkOrgApps($params['userid']);

            if ((int) $data['count'] > 0) {
                // $this->log([
                //     'Location' => __METHOD__.'() : 1',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                // ]);

                $data['apps'] = $this->getOrgApps($params['userid']);

                if (sizeof($data) > 0) {
                    foreach ($data['apps'] as $key => $app) {
                        $sql = 'select am.moduleid, am.title, am.controller, am.method, 
                                       case when isnull(am.submoduleid) then am.moduleid else am.submoduleid end as submoduleid
                                  from hierachy_module_users hmu
                                  join application_modules am
                                    on hmu.moduleid = am.moduleid
                                 where am.appid = ?
                                   and hmu.userid = ?
                                 order by submoduleid, am.moduleid;';
                        $result = $this->db->query([
                            $key,
                            $params['userid'],
                        ], $sql, __METHOD__);

                        foreach ($result as $res) {
                            $data['data'][$key]['menu'][$res->moduleid] = [
                                'moduleid' => $res->moduleid,
                                'title' => $res->title,
                                'controller' => $res->controller,
                                'method' => $res->method,
                                'submoduleid' => $res->submoduleid,
                            ];
                        }
                    }

                    $data['data'] = $this->callsLoadApps($params, $data);

                    // $this->log([
                    //     'Location' => __METHOD__.'() : 2',
                    //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                    //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                    // ]);
                }
            }

            $this->log([
                'Location' => __METHOD__.'() : 3',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);

            if (null === $data) {
                throw new Exception('Unable to query application_users');
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

    protected function checkOrgApps($userid)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            $sql = 'select count(distinct am.appid) as count
                      from hierachy_module_users hmu
                      join application_modules  am
                        on hmu.moduleid = am.moduleid
                     where hmu.userid = ?;';

            $result = $this->db->query([
                $userid,
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['count'] = $res->count;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'userid' => $userid,
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

    protected function checkPersonnelApps($userid)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            $sql = 'select count(*) as count 
                      from application_users 
                     where userid = ?;';

            $result = $this->db->query([
                $userid,
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['count'] = $res->count;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'userid' => $userid,
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

    protected function getPersonnelApps($userid): array
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = [];

        try {
            $sql = 'select u.appid, a.name 
                      from application_users u 
                      join applications a 
                        on u.appid = a.appid 
                     where u.userid = ?
                     group by u.appid;';

            $result = $this->db->query([
                $userid,
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data[$res->appid]['name'] = $res->name;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $data = [];

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    protected function getOrgApps($userid): array
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = [];

        try {
            $sql = 'select a.appid, a.name
                      from hierachy_module_users hmu
                      join application_modules  am
                        on hmu.moduleid = am.moduleid
                      join applications a
                        on am.appid = a.appid
                     where hmu.userid = ?
                     group by a.appid;';

            $result = $this->db->query([
                $userid,
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data[$res->appid]['name'] = $res->name;
            }
            $this->log([
                'Location' => __METHOD__.'()',
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $data = [];

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    protected function callsLoadApps($params, $data)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        $result = [];

        try {
            $cnt = 0;
            foreach ($data['data'] as $appid => $app) {
                $result[$appid]['name'] = $data['apps'][$appid]['name'];

                foreach ($app['menu'] as $moduleid => $menu) {
                    $result[$appid]['menu'][$moduleid] = [
                        'moduleid' => $menu['moduleid'],
                        'title' => $menu['title'],
                        'controller' => $this->encrypt->scramble($data['apps'][$appid]['name'].'\\'.$menu['controller'].'\\'.$menu['controller'], 'controller', $params['userid']),
                        'method' => $this->encrypt->scramble($menu['method'], 'method', $params['userid']),
                        'submoduleid' => $menu['submoduleid'],
                    ];

                    switch (true) {
                        case null === $result[$appid]['menu'][$moduleid]['controller']:
                            throw new Exception('Encryption failed of controller: '.$menu['controller']);

                            break;

                        case null === $result[$appid]['menu'][$moduleid]['method']:
                            throw new Exception('Encryption failed of method: '.$menu['method']);

                            break;
                    }
                }
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'data' => json_encode($data, JSON_PRETTY_PRINT),
                'result' => json_encode($result, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $result = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $result;
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
