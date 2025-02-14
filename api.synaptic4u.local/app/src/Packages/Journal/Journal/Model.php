<?php

namespace Synaptic4U\Packages\Journal\Journal;

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

    public function create(&$params)
    {
        
        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            $params['formArray'][0] = $this->encrypt->unscramble($params['formArray'][0], 'userid');

            if (null === $params['formArray'][0]) {
                throw new Exception('Decryption failed of userid');
            }

            $sections = $this->fetchsections($params);

            if (null === $sections) {
                throw new Exception('fetchsections returning null');
            }

            $data = $sections;

            $this->log([
                'Location' => __METHOD__.'()',
                'data' => json_encode($data),
                'params' => json_encode($params),
            ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    // Fetches the sections to be used to show journal entry
    public function fetchsections($params)
    {
        
        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            $sql = 'select title 
                      from journal_section
                     where userid = ?
                       and active = 1
                     order by orderby;';

            $result = $this->db->query([
                $params['formArray'][0],
            ], $sql);

            foreach ($result as $res) {
                $data[] = $res->title;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'data' => json_encode($data),
                'result' => json_encode($result),
                'params' => json_encode($params),
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

    // Fetches the sections to be used to store journal entry
    public function fetch_store_sections($params)
    {
        
        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            $sql = 'select sectionid, title 
                      from journal_section 
                     where userid = ?
                       and active = 1
                     order by orderby';

            $result = $this->db->query([
                $params['userid'],
            ], $sql);

            foreach ($result as $res) {
                $data[$res->sectionid] = $res->title;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'data' => json_encode($data),
                'result' => json_encode($result),
                'params' => json_encode($params),
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

    // Stores the new Journal to db
    public function store($params)
    {
        
        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            $size = sizeof($params['formArray']);

            $insert = 'insert into journal(journalid, userid, datedon';

            $values = 'values(null, ?, now()';

            $sqlParams[0] = $params['userid'];

            for ($cnt = 0; $cnt < $size; ++$cnt) {
                $cnt2 = $cnt + 1;

                $sqlParams[$cnt2] = $params['formArray'][$cnt];

                $insert .= ',section'.$cnt2.' ';

                $values .= ', ?';
            }

            $insert .= ')';

            $values .= ');';

            $sql = $insert.$values;

            $this->log([
                'Location' => __METHOD__.'()',
                'size' => $size,
                'params' => json_encode($params),
            ]);

            $result = $this->db->query($sqlParams, $sql);

            $journalid = $this->db->getLastId();

            $sections = $this->fetch_store_sections($params);

            if (null === $sections) {
                throw new Exception('fetch_store_sections returning null');
            }

            $sql = 'insert into journal_sections(id, journalid, sectionid)values(null, ?, ?)';

            foreach ($sections as $key => $value) {
                $result = $this->db->query([$journalid, $key], $sql);
            }

            $sql = 'select datedon from journal where journalid = ?';

            $result = $this->db->query([$journalid], $sql);

            foreach ($result as $res) {
                $datedon = $res->datedon;
            }

            $data = [
                'journalid' => $journalid,
                'datedon' => $datedon,
            ];

            $this->log([
                'Location' => __METHOD__.'()',
                'JournalId' => $journalid,
                'result' => json_encode($result),
                'params' => json_encode($params),
                'data' => json_encode($data),
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

    public function show(&$params)
    {
        
        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            $params['formArray'][0] = $this->encrypt->unscramble($params['formArray'][0], 'userid');

            $params['formArray'][1] = $this->encrypt->unscramble($params['formArray'][1], 'journalid');

            if (null === $params['formArray'][0]) {
                throw new Exception('Decryption failed of userid');
            }

            if (null === $params['formArray'][1]) {
                throw new Exception('Decryption failed of journalid!!');
            }

            $sections = $this->fetchsections($params);

            if (null === $sections) {
                throw new Exception('fetchsections returning null');
            }

            $sql = 'select j.journalid, j.datedon, j.userid, u.firstname, u.surname';

            foreach ($sections as $key => $value) {
                $sql .= ', j.section'.($key + 1);
            }

            $sql .= ' 
                      from journal j
                      join users u
                        on j.userid = u.userid
                     where j.userid = ?
                       and j.journalid = ?';

            $result = $this->db->query([
                $params['formArray'][0],
                $params['formArray'][1],
            ], $sql);

            foreach ($result as $res) {
                $data['details'] = [
                    'datedon' => $res->datedon,
                    'userid' => $this->encrypt->scramble($res->userid, 'userid', $params['userid']),
                    'journalid' => $this->encrypt->scramble($res->journalid, 'journalid', $params['userid']),
                    'user' => $res->firstname.' '.$res->surname,
                ];

                foreach ($sections as $key => $value) {
                    $name = 'section'.($key + 1);
                    $data['journal'][$value] = $res->{$name};
                }
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'data' => json_encode($data),
                'params' => json_encode($params),
            ]);
        } catch (Exception $e) {
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
            $userid = $this->encrypt->unscramble(array_shift($params['formArray']), 'userid');

            $journalid = $this->encrypt->unscramble(array_shift($params['formArray']), 'journalid');

            if (null === $userid) {
                throw new Exception('Decryption failed of userid');
            }
            if (null === $journalid) {
                throw new Exception('Decryption failed of journalid!!');
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'userid' => $userid,
                'JournalId' => $journalid,
                'params' => json_encode($params),
            ]);

            if ((int) $userid === (int) $params['userid']) {
                $sqlParams = [];

                $sql = 'update journal
                           set datedon = now()';

                foreach ($params['formArray'] as $key => $value) {
                    $sql .= ', section'.($key + 1).' = ?';

                    $sqlParams[] = $value;
                }

                $sql .= '
                         where journalid = ?
                           and userid = ?';

                $sqlParams[] = $journalid;

                $sqlParams[] = $userid;

                $this->log([
                    'Location' => __METHOD__.'()',
                    'sql' => $sql,
                    'params' => json_encode($params),
                    '$sqlParams' => json_encode($sqlParams),
                ]);

                $this->db->query($sqlParams, $sql);
            }

            $data = $this->db->getLastId();
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

            $calls['Journal'] = $this->encrypt->scramble('Journal\Journal\Journal', 'controller', $params['userid']);

            $calls['load'] = $this->encrypt->scramble('load', 'method', $params['userid']);

            $calls['Dashboard'] = $this->encrypt->scramble('Journal\Dashboard\Dashboard', 'controller', $params['userid']);

            $calls['loadlist'] = $this->encrypt->scramble('loadlist', 'method', $params['userid']);

            $calls['ConfigJournal'] = $this->encrypt->scramble('Journal\ConfigJournal\ConfigJournal', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['store']:
                    throw new Exception('Encryption failed of store');

                    break;

                case null === $calls['Journal']:
                    throw new Exception('Encryption failed of Journal');

                    break;

                case null === $calls['load']:
                    throw new Exception('Encryption failed of load');

                    break;

                case null === $calls['Dashboard']:
                    throw new Exception('Encryption failed of Dashboard');

                    break;

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

    public function callsStore($params)
    {
        
        $calls = null;

        try {
            $calls['load'] = $this->encrypt->scramble('load', 'method', $params['userid']);

            $calls['JournalList'] = $this->encrypt->scramble('Journal\JournalList\JournalList', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['load']:
                    throw new Exception('Encryption failed of load');

                    break;

                case null === $calls['JournalList']:
                    throw new Exception('Encryption failed of JournalList');

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

            $calls['Journal'] = $this->encrypt->scramble('Journal\Journal\Journal', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['edit']:
                    throw new Exception('Encryption failed of edit');

                    break;

                case null === $calls['Journal']:
                    throw new Exception('Encryption failed of Journal');

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
            $calls['load'] = $this->encrypt->scramble('load', 'method', $params['userid']);

            $calls['JournalList'] = $this->encrypt->scramble('Journal\JournalList\JournalList', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['load']:
                    throw new Exception('Encryption failed of load');

                    break;

                case null === $calls['JournalList']:
                    throw new Exception('Encryption failed of JournalList');

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

            $calls['Journal'] = $this->encrypt->scramble('Journal\Journal\Journal', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['update']:
                    throw new Exception('Encryption failed of update');

                    break;

                case null === $calls['Journal']:
                    throw new Exception('Encryption failed of Journal');

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