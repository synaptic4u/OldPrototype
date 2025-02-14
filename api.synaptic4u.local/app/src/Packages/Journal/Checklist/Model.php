<?php

namespace Synaptic4U\Packages\Journal\Checklist;

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

            $this->log([
                'Location' => __METHOD__.'()',
            ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            return null;
        }
    }

    // Checks if it exists
    public function exists($params)
    {

        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            $sql = 'select count(*) as count
                      from checklist 
                     where userid = ? 
                       and deleted = 0;';

            $result = $this->db->query([$params['userid']], $sql);

            foreach ($result as $res) {
                $data = $res->count;
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

    // Stores the new Checklist to db
    public function store($params)
    {

        $datedon = null;

        $checklistid = null;

        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
    ]);

        try {
            $sql = 'insert into checklist(checklistid, userid, datedon, deleted, qoutes, list)
                    values(null, ?, now(), 0, ?, ?);';

            $result = $this->db->query([
                $params['userid'],
                $params['formArray']['quotes'],
                $params['formArray']['list'],
            ], $sql);

            $checklistid = $this->db->getLastId();

            $sql = 'select datedon from checklist where checklistid = ?;';

            $result = $this->db->query([$checklistid], $sql);

            foreach ($result as $res) {
                $datedon = $res->datedon;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'checklistid' => $checklistid,
                'datedon' => $datedon,
                'params' => json_encode($params),
            ]);

            $data = [
                'checklistid' => $checklistid,
                'datedon' => $datedon,
            ];
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

    public function show(&$params)
    {

        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            // $userid = $this->encrypt->unscramble($params['userid'], 'userid');

            $sql = 'select checklistid, qoutes, list, datedon
                      from checklist 
                     where userid = ? 
                       and deleted = 0';

            $result = $this->db->query([
                $params['userid'],
            ], $sql);

            foreach ($result as $res) {
                $data = [
                    'datedon' => $res->datedon,
                    'checklistid' => $this->encrypt->scramble($res->checklistid, 'checklistid', $params['userid']),
                    'qoutes' => $res->qoutes,
                    'list' => $res->list,
                ];
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

    public function update($params)
    {

        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            $checklistid = $this->encrypt->unscramble($params['formArray']['param'], 'checklistid');

            if (null === $checklistid) {
                throw new Exception('Decryption failed of checklistid');
            }

            $sql = 'update checklist
                       set qoutes = ?, list = ?, datedon = now() 
                     where checklistid = ?
                       and userid = ?
                       and deleted = 0;';

            $result = $this->db->query([
                $params['formArray']['quotes'],
                $params['formArray']['list'],
                $checklistid,
                $params['userid'],
            ], $sql);

            $sql = 'select datedon from checklist where checklistid = ?;';

            $result = $this->db->query([$checklistid], $sql);

            foreach ($result as $res) {
                $datedon = $res->datedon;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'checklistid' => $checklistid,
                'datedon' => $datedon,
                'params' => json_encode($params),
            ]);

            $data = [
                'checklistid' => $checklistid,
                'datedon' => $datedon,
            ];
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

    public function callsCreate($params)
    {

        $calls = null;

        try {
            $calls['store'] = $this->encrypt->scramble('store', 'method', $params['userid']);

            $calls['Checklist'] = $this->encrypt->scramble('Journal\Checklist\Checklist', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['store']:
                    throw new Exception('Encryption failed of store');

                    break;

                case null === $calls['Checklist']:
                    throw new Exception('Encryption failed of Checklist');

                    break;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'calls' => json_encode($calls),
            ]);
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

        $calls = null;

        try {
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            $calls['Checklist'] = $this->encrypt->scramble('Journal\Checklist\Checklist', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['show']:
                    throw new Exception('Encryption failed of show');

                    break;

                case null === $calls['Checklist']:
                    throw new Exception('Encryption failed of Checklist');

                    break;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'calls' => json_encode($calls),
            ]);
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

        $calls = null;

        try {
            $calls['edit'] = $this->encrypt->scramble('edit', 'method', $params['userid']);

            $calls['Checklist'] = $this->encrypt->scramble('Journal\Checklist\Checklist', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['edit']:
                    throw new Exception('Encryption failed of edit');

                    break;

                case null === $calls['Checklist']:
                    throw new Exception('Encryption failed of Checklist');

                    break;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'calls' => json_encode($calls),
            ]);
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

        $calls = null;

        try {
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            $calls['Checklist'] = $this->encrypt->scramble('Journal\Checklist\Checklist', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['show']:
                    throw new Exception('Encryption failed of show');

                    break;

                case null === $calls['Checklist']:
                    throw new Exception('Encryption failed of Checklist');

                    break;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'calls' => json_encode($calls),
            ]);
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

        $calls = null;

        try {
            $calls['update'] = $this->encrypt->scramble('update', 'method', $params['userid']);

            $calls['Checklist'] = $this->encrypt->scramble('Journal\Checklist\Checklist', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['update']:
                    throw new Exception('Encryption failed of update');

                    break;

                case null === $calls['Checklist']:
                    throw new Exception('Encryption failed of Checklist');

                    break;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'calls' => json_encode($calls),
            ]);
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
        new Log($msg);
    }
}