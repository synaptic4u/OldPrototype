<?php

namespace Synaptic4U\Packages\Journal\ConfigJournal;

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

    // Stores the new Journal sections to db
    public function store($params)
    {

        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            $sql = 'select count(*) as Cnt 
                      from journal_section 
                     where userid = ?
                       and active = 1';

            $result = $this->db->query([
                $params['userid'],
            ], $sql);

            foreach ($result as $res) {
                $cnt = $res->Cnt;
            }

            if ($cnt < 9) {
                $this->log([
                    'Location' => __METHOD__.'()',
                    'cnt' => $cnt,
                ]);

                $sql = 'insert into journal_section(sectionid, userid, datedon, title, orderby, active) 
                        values(null, ?, now(), ?, ?, 1);';

                $result = $this->db->query([
                    $params['userid'],
                    $params['formArray']['title'],
                    $cnt + 1,
                ], $sql);

                $sectionid = $this->db->getLastId();

                $sql = 'select datedon from journal_section where sectionid = ?';

                $result = $this->db->query([
                    $sectionid,
                ], $sql);

                foreach ($result as $res) {
                    $datedon = $res->datedon;
                }

                $data = [
                    'sectionid' => $sectionid,
                    'datedon' => $datedon,
                ];

                $this->log([
                    'Location' => __METHOD__.'()',
                    'data' => json_encode($data),
                    'params' => json_encode($params),
                ]);
            } else {
                $data = [
                    'sectionid' => -1,
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

    public function show($params)
    {
        
        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            $sectionid = $this->encrypt->unscramble($params['formArray'][0], 'sectionid');

            if (null === $sectionid) {
                throw new Exception('Decryption failed of sectionid');
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'sectionid' => $sectionid,
                'params' => json_encode($params),
            ]);

            $sql = 'select sectionid, datedon, title
                      from journal_section
                     where userid = ?
                       and active = 1
                       and sectionid = ?';

            $result = $this->db->query([
                $params['userid'],
                $sectionid,
            ], $sql);

            foreach ($result as $res) {
                $data = [
                    'sectionid' => $this->encrypt->scramble($res->sectionid, 'sectionid', $params['userid']),
                    'datedon' => $res->datedon,
                    'title' => $res->title,
                ];

                if (null === $data['sectionid']) {
                    throw new Exception('Encryption failed of sectionid');
                }
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'data' => json_encode($data),
                'params' => json_encode($params),
            ]);
        } catch (Exception $e) {
            $data = null;

            $this->error([
                '$e' => $e->__toString(),
                'Location' => __METHOD__.'()',
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
            $sectionid = $this->encrypt->unscramble($params['formArray']['param'], 'sectionid');

            if (null === $sectionid) {
                throw new Exception('Decryption failed of sectionid');
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'sectionid' => $sectionid,
                'params' => json_encode($params),
            ]);

            $sql = 'update journal_section
                       set datedon = now(), title = ?
                     where sectionid = ?
                       and active = 1
                       and userid = ?';

            $result = $this->db->query([
                $params['formArray']['title'],
                $sectionid,
                $params['userid'],
            ], $sql);

            $data = $this->db->getLastId();
        } catch (Exception $e) {
            $data = null;

            $this->error([
                '$e' => $e->__toString(),
                'Location' => __METHOD__.'()',
            ]);
        } finally {
            return $data;
        }
    }

    public function loadlist($params)
    {
        
        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            $data = $this->checklist($params);

            if (null === $data) {
                throw new Exception('Could not count sections from journal_sections');
            }
            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params),
                'data' => json_encode($data),
            ]);

            if ((int) $data['cnt'] > 0) {
                $sql = 'select sectionid, title, orderby, datedon
                        from journal_section
                        where userid = ?
                        and active = 1
                        order by orderby ASC';

                $result = $this->db->query(
                    [$params['userid']],
                    $sql
                );

                foreach ($result as $res) {
                    $data[$res->orderby] = [
                        'sectionid' => $this->encrypt->scramble($res->sectionid, 'sectionid', $params['userid']),
                        'datedon' => $res->datedon,
                        'title' => $res->title,
                    ];

                    if (null === $data[$res->orderby]['sectionid']) {
                        throw new Exception('Encryption failed of sectionid');
                    }
                }
            }
        } catch (Exception $e) {
            $data = null;

            $this->error([
                '$e' => $e->__toString(),
                'Location' => __METHOD__.'()',
            ]);
        } finally {
            return $data;
        }
    }

    /**
     * Checks to see if there are sections for the users journal.
     *
     * @param mixed $params
     *
     * @return array
     *
     * Returns array with count set to cnt = 0 or cnt = > 0
     */
    public function checklist($params)
    {
        
        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            $sql = 'select count(*) as Cnt 
                      from journal_section 
                     where userid = ?';

            $result = $this->db->query([
                $params['userid'],
            ], $sql);

            foreach ($result as $res) {
                $data['cnt'] = $res->Cnt;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params),
                'data' => json_encode($data),
            ]);
        } catch (Exception $e) {
            $data = null;

            $this->error([
                '$e' => $e->__toString(),
                'Location' => __METHOD__.'()',
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

            $calls['ConfigJournal1'] = $this->encrypt->scramble('Journal\ConfigJournal\ConfigJournal', 'controller', $params['userid']);

            $calls['loadlist'] = $this->encrypt->scramble('loadlist', 'method', $params['userid']);

            $calls['ConfigJournal2'] = $this->encrypt->scramble('Journal\ConfigJournal\ConfigJournal', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['store']:
                    throw new Exception('Encryption failed of store');

                    break;

                case null === $calls['ConfigJournal1']:
                    throw new Exception('Encryption failed of ConfigJournal1');

                    break;

                case null === $calls['loadlist']:
                    throw new Exception('Encryption failed of loadlist');

                    break;

                case null === $calls['ConfigJournal2']:
                    throw new Exception('Encryption failed of ConfigJournal2');

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
            $calls['loadlist'] = $this->encrypt->scramble('loadlist', 'method', $params['userid']);

            $calls['ConfigJournal'] = $this->encrypt->scramble('Journal\ConfigJournal\ConfigJournal', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['loadlist']:
                    throw new Exception('Encryption failed of loadlist');

                    break;

                case null === $calls['ConfigJournal']:
                    throw new Exception('Encryption failed of ConfigJournal');

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
            $calls['update'] = $this->encrypt->scramble('update', 'method', $params['userid']);

            $calls['ConfigJournal1'] = $this->encrypt->scramble('Journal\ConfigJournal\ConfigJournal', 'controller', $params['userid']);

            $calls['loadlist'] = $this->encrypt->scramble('loadlist', 'method', $params['userid']);

            $calls['ConfigJournal2'] = $this->encrypt->scramble('Journal\ConfigJournal\ConfigJournal', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['update']:
                    throw new Exception('Encryption failed of update');

                    break;

                case null === $calls['ConfigJournal1']:
                    throw new Exception('Encryption failed of ConfigJournal1');

                    break;

                    case null === $calls['loadlist']:
                        throw new Exception('Encryption failed of loadlist');

                        break;

                    case null === $calls['ConfigJournal2']:
                        throw new Exception('Encryption failed of ConfigJournal2');

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
            $calls['loadlist'] = $this->encrypt->scramble('loadlist', 'method', $params['userid']);

            $calls['ConfigJournal'] = $this->encrypt->scramble('Journal\ConfigJournal\ConfigJournal', 'controller', $params['userid']);

            switch (true) {
                    case null === $calls['loadlist']:
                        throw new Exception('Encryption failed of loadlist');

                        break;

                    case null === $calls['ConfigJournal']:
                        throw new Exception('Encryption failed of ConfigJournal');

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
            $calls['loadlist'] = $this->encrypt->scramble('loadlist', 'method', $params['userid']);

            $calls['ConfigJournal'] = $this->encrypt->scramble('Journal\ConfigJournal\ConfigJournal', 'controller', $params['userid']);

            switch (true) {
                    case null === $calls['loadlist']:
                        throw new Exception('Encryption failed of loadlist');

                        break;

                    case null === $calls['ConfigJournal']:
                        throw new Exception('Encryption failed of ConfigJournal');

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

    public function callsLoadList($params)
    {
        
        $calls = null;

        try {
            $calls['create'] = $this->encrypt->scramble('create', 'method', $params['userid']);

            $calls['ConfigJournal1'] = $this->encrypt->scramble('Journal\ConfigJournal\ConfigJournal', 'controller', $params['userid']);

            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            $calls['ConfigJournal2'] = $this->encrypt->scramble('Journal\ConfigJournal\ConfigJournal', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['create']:
                    throw new Exception('Encryption failed of create');

                    break;

                case null === $calls['ConfigJournal1']:
                    throw new Exception('Encryption failed of ConfigJournal1');

                    break;

                case null === $calls['show']:
                    throw new Exception('Encryption failed of show');

                    break;

                case null === $calls['ConfigJournal2']:
                    throw new Exception('Encryption failed of ConfigJournal2');

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