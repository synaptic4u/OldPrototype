<?php

namespace Synaptic4U\Packages\Hierachy\Base;

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

            $path = dirname(__FILE__, 1).'/Models/';

            $this->validate = new Validate($path);

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'Dir' => dirname(__FILE__, 1),
            //     'path' => $path,
            // ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        }
    }

    /**
     * @param mixed $params
     */
    public function checkStatus($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            $sql = 'select count(*) as count from hierachy_users where userid = ?;';
            $result = $this->db->query([
                $params['userid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data['count'] = $res->count;
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
        }

        return $data;
    }

    /**
     * @param mixed $params
     */
    public function getHierachy($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            // Second proc param chooses the result type. {1:minimum for the company and logo, 2: maximum for all the hierachy details.}
            $test = 0;
            if (1 === $test) {
                $sql = 'call GetHierachy(?, 1);';

                $result = $this->db->callProc([
                    $params['userid'],
                ], $sql, __METHOD__);
            } else {
                $sql = 'select min(h.hierachyid) as hierachyid, h.hierachysubid, hd.name, case when isnull(hi.logo) then "default" else hi.logo end as logo
                        from hierachy_users hu
                        JOIN hierachy h
                            ON hu.hierachyid = h.hierachyid
                        join hierachy_det hd 
                            on h.hierachyid = hd.hierachyid 
                        left join hierachy_particulars hp
                            on hd.detid = hp.detid
                        left join hierachy_images hi
                            on hp.particularid = hi.particularid
                        where hu.userid = ?
                        order by h.hierachysubid, h.hierachyid;';

                $result = $this->db->query([
                    $params['userid'],
                ], $sql, __METHOD__);
            }

            foreach ($result as $res) {
                $data[] = [
                    'id' => $res->hierachyid,
                    'subid' => $res->hierachysubid,
                    'hierachyid' => $this->encrypt->scramble($res->hierachyid, 'hierachyid', $params['userid']),
                    'hierachysubid' => $this->encrypt->scramble($res->hierachysubid, 'hierachysubid', $params['userid']),
                    'name' => $res->name,
                    'logo' => $res->logo,
                ];
            }

            $this->log([
                'Location' => __METHOD__.'() DEBUG - GetHierachy',
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

    /**
     * @param mixed $params
     */
    public function show($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            // Second proc param chooses the result type. {1:minimum for the company and logo, 2: maximum for all the hierachy details.}
            $sql = 'call GetHierachy(?, 2);';
            $result = $this->db->callProc([
                $params['userid'],
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data[] = [
                    'id' => $res->hierachyid,
                    'subid' => $res->hierachysubid,
                    'hierachyid' => $this->encrypt->scramble($res->hierachyid, 'hierachyid', $params['userid']),
                    'hierachysubid' => $this->encrypt->scramble($res->hierachysubid, 'hierachysubid', $params['userid']),
                    'levelid' => $res->levelid,
                    'name' => $res->name,
                ];
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
        }

        return $data;
    }

    /**
     * @param mixed $params
     */
    public function detail($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            if (isset($params['formArray']) && sizeof($params['formArray']) >= 1) {
                $hierachyid = $this->encrypt->unscramble($params['formArray'][0], 'hierachyid');
                // $hierachysubid = $this->encrypt->unscramble($params['formArray'][1], 'hierachysubid');
            }
            if (null === $hierachyid) {
                throw new Exception('Error decrypting hierachyid');
            }
            // if (null === $hierachysubid) {
            //     throw new Exception('Error decrypting hierachysubid');
            // }

            $sql = 'select h.hierachyid, 
                           concat(u.firstname, " ", u.surname) as hierachycreator,
                           hd.detid, hd.name as org
                      from hierachy h
                      join hierachy_det hd
                        on h.hierachyid = hd.hierachyid
                      join users u
                        on h.userid = u.userid
                     where h.hierachyid = ?;';

            $result = $this->db->query([
                $hierachyid,
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data = [
                    'hierachyid' => $this->encrypt->scramble($res->hierachyid, 'hierachyid', $params['userid']),
                    'detid' => $this->encrypt->scramble($res->detid, 'detid', $params['userid']),
                    'hierachycreator' => $res->hierachycreator,
                    'org' => $res->org,
                ];
            }

            $this->log([
                'Location' => __METHOD__.'()',
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

    /**
     * @param mixed $params
     */
    public function callsDetail($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['subhierachy'] = $this->encrypt->scramble('Hierachy\Hierachy\Hierachy', 'controller', $params['userid']);
            $calls['subshow'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['subhierachy']:
                    throw new Exception('Encryption failed of method: subhierachy');

                    break;

                case null === $calls['subshow']:
                    throw new Exception('Encryption failed of method: subshow');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__.'()',
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

    /**
     * @param mixed $params
     */
    public function callsShow($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['hierachy'] = $this->encrypt->scramble('Hierachy\Base\Hierachy', 'controller', $params['userid']);
            $calls['subhierachy'] = $this->encrypt->scramble('Hierachy\Hierachy\Hierachy', 'controller', $params['userid']);
            $calls['detail'] = $this->encrypt->scramble('detail', 'method', $params['userid']);
            $calls['create'] = $this->encrypt->scramble('create', 'method', $params['userid']);
            $calls['nested'] = $this->encrypt->scramble('1', 'param', $params['userid']);

            switch (true) {
                case null === $calls['hierachy']:
                    throw new Exception('Encryption failed of controller: Hierachy');

                    break;

                case null === $calls['subhierachy']:
                    throw new Exception('Encryption failed of controller: subhierachy');

                    break;

                case null === $calls['detail']:
                    throw new Exception('Encryption failed of method: detail');

                    break;

                case null === $calls['create']:
                    throw new Exception('Encryption failed of method: create');

                    break;

                case null === $calls['nested']:
                    throw new Exception('Encryption failed of method: nested');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__.'()',
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

    /**
     * @param mixed $params
     */
    public function callsBlank($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['hierachy'] = $this->encrypt->scramble('Hierachy\Base\Hierachy', 'controller', $params['userid']);
            $calls['create'] = $this->encrypt->scramble('create', 'method', $params['userid']);

            switch (true) {
                case null === $calls['hierachy']:
                    throw new Exception('Encryption failed of controller: Hierachy');

                    break;

                case null === $calls['create']:
                    throw new Exception('Encryption failed of method: create');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__.'()',
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

    /**
     * @param mixed $params
     */
    public function callsExists($params)
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
            //     'Location' => __METHOD__.'()',
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
