<?php

namespace Synaptic4U\Packages\Journal\JournalList;

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

    /**
     *  Counts any journal entries belonging to the user.
     *  Uses the userid of the active user.
     *
     * @param mixed $params
     *
     *  @return array
     *  Returns array with count set to cnt = 0 or cnt = > 0
     */
    public function checklist($params)
    {
        $data = null;

        try {
            $sql = 'select count(*) as Cnt 
                      from journal 
                     where userid = ?';

            $result = $this->db->query([
                $params['userid'],
            ], $sql);

            foreach ($result as $res) {
                $data['cnt'] = $res->Cnt;
            }

            if (null === $data['cnt']) {
                throw new Exception('Returned data[cnt] is null');
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'data' => json_encode($data),
                'params' => json_encode($params),
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

    /**
     * Fetches the users name & surname
     * Adds encrypted @userid as a param to list
     * Fetches a list of journal entry dates.
     *
     * @param mixed $params
     *
     * @return array
     *               [0] = user name & surname
     *               [1] = encrypted userid
     *               [2>] = journal entry dates
     */
    public function loadlist($params)
    {
        
        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            $sql = 'select firstname, surname 
                      from users 
                     where userid = ?';

            $result = $this->db->query([
                $params['userid'],
            ], $sql);

            //  Fetches the users name & surname
            foreach ($result as $key => $res) {
                $data[$key] = $res->firstname.' '.$res->surname;

                if (null === $data[$key]) {
                    throw new Exception('Returned null on users details');
                }
            }

            //  Adds encrypted @userid as a param to list
            $userid = $this->encrypt->scramble($params['userid'], 'userid', $params['userid']);

            if (null === $userid) {
                throw new Exception('Encryption failed for userid');
            }

            $data[] = $userid;

            //  Fetches a list of journal entry dates
            $sql = 'select datedon
                      from journal
                     where userid = ?
                     order by datedon DESC';

            $result = $this->db->query([
                $params['userid'],
            ], $sql);

            $cnt = sizeof($data);

            foreach ($result as $key => $res) {
                $index = $cnt + $key;

                $data[$index] = [
                    'datedon' => $this->encrypt->scramble($res->datedon, 'datedon', $params['userid']),
                ];

                if (null === $data[$index]['datedon']) {
                    throw new Exception('Encryption failed for datedon');
                }
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'data' => json_encode($data, JSON_PRETTY_PRINT),
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'cnt' => $cnt,
                'sizeof($data)' => sizeof($data),
            ]);
        } catch (Exception $e) {
            $data = null;

            $this->error([
                'Location' => __METHOD__.'()',
                'Exception' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    public function pagination($params)
    {
        
        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            $userid = (isset($params['formArrray'][0])) ? $this->encrypt->unscramble($params['formArray'][0], 'userid') : $params['userid'];

            if (null === $userid) {
                throw new Exception('Decryption failed for userid');
            }

            $data[0] = $this->encrypt->scramble($userid, 'userid', $params['userid']);

            if (null === $data[0]) {
                throw new Exception('Encryption failed for userid');
            }

            // Note!!! -> Add journal section
            $sql = 'select journalid, datedon, section1
                      from journal
                     where userid = ?
                       and datedon >= ?
                       and datedon <= ?
                     order by datedon DESC';

            $datedon1 = $this->encrypt->unscramble($params['formArray'][1], 'datedon', $params['userid']);

            if (null === $datedon1) {
                throw new Exception('Decryption failed for datedon1');
            }

            $datedon2 = $this->encrypt->unscramble($params['formArray'][2], 'datedon', $params['userid']);

            if (null === $datedon2) {
                throw new Exception('Decryption failed for datedon2');
            }

            $result = $this->db->query([
                $userid,
                $datedon2,
                $datedon1,
            ], $sql);

            $cnt = sizeof($data);

            foreach ($result as $key => $res) {
                $index = $cnt + $key;
                $data[$index] = [
                    'journalid' => $this->encrypt->scramble($res->journalid, 'journalid', $params['userid']),
                    'datedon' => $res->datedon,
                    'events' => $res->section1,
                ];

                if (null === $data[$index]['journalid']) {
                    throw new Exception('Encryption failed for journalid');
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
                'Location' => __METHOD__.'()',
                'Exception' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    public function loadShared($params)
    {
        
        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            // Query works ok
            $sql = 'select count(*) as count
                      from journal j
                      join journal_shared js
                        on js.userid = j.userid
                     where js.my_userid = ?;';

            $result = $this->db->query([
                $params['userid'],
            ], $sql);

            foreach ($result as $res) {
                $data['count'] = $res->count;
            }

            if (null === $data['count']) {
                throw new Exception('Count returned null');
            }

            if (((int) $data['count']) > 0) {
                $this->log([
                    'Location' => __METHOD__.'()',
                    'params' => json_encode($params),
                    '(((int) $data[count]) > 0)' => (((int) $data['count']) > 0),
                ]);

                $sql = 'select j.datedon
                          from journal j
                          join journal_shared js
                            on js.userid = j.userid
                         where js.my_userid = ?
                         order by datedon DESC;';

                $result = $this->db->query([
                    $params['userid'],
                ], $sql);

                //  Adds encrypted @userid as a param to list
                $userid = $this->encrypt->scramble($params['userid'], 'userid', $params['userid']);

                if (null === $userid) {
                    throw new Exception('Encryption failed for userid');
                }

                $data[] = $userid;

                $cnt = sizeof($data);

                foreach ($result as $key => $res) {
                    $index = $cnt + $key;

                    $data[$index] = [
                        'datedon' => $this->encrypt->scramble($res->datedon, 'datedon', $params['userid']),
                    ];

                    if (null === $data[$index]['datedon']) {
                        throw new Exception('Encryption failed for datedon');
                    }
                }

                $this->log([
                    'Location' => __METHOD__.'()',
                    'params' => json_encode($params),
                    'data' => json_encode($data),
                ]);
            }
        } catch (Exception $e) {
            $data = null;

            $this->error([
                'Location' => __METHOD__.'()',
                'Exception' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    public function paginationShared($params)
    {
        
        $data = null;

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($params),
        ]);

        try {
            $userid = (isset($params['formArrray'][0])) ? $this->encrypt->unscramble($params['formArray'][0], 'userid') : $params['userid'];

            if (null === $userid) {
                throw new Exception('Decryption failed for userid');
            }

            $data[] = $this->encrypt->scramble($userid, 'userid', $params['userid']);

            if (null === $data[0]) {
                throw new Exception('Encryption failed for userid');
            }

            // Note!!! -> Add journal section
            $sql = 'select j.userid, j.journalid, j.datedon, j.section1, u.firstname, u.surname
                      from journal j
                      join journal_shared js
                        on js.userid = j.userid
                      join users u
                        on j.userid = u.userid
                     where js.my_userid = ?
                       and j.datedon >= ?
                       and j.datedon <= ?
                     order by j.datedon DESC;';

            $datedon1 = $this->encrypt->unscramble($params['formArray'][1], 'datedon', $params['userid']);

            if (null === $datedon1) {
                throw new Exception('Decryption failed for datedon1');
            }

            $datedon2 = $this->encrypt->unscramble($params['formArray'][2], 'datedon', $params['userid']);

            if (null === $datedon2) {
                throw new Exception('Decryption failed for datedon2');
            }

            $result = $this->db->query([
                $userid,
                $datedon2,
                $datedon1,
            ], $sql);

            $cnt = sizeof($data);

            foreach ($result as $key => $res) {
                $index = $cnt + $key;

                $data[$index] = [
                    'journalid' => $this->encrypt->scramble($res->journalid, 'journalid', $params['userid']),
                    'userid' => $this->encrypt->scramble($res->userid, 'userid', $params['userid']),
                    'datedon' => $res->datedon,
                    'events' => $res->section1,
                    'name' => $res->firstname.' '.$res->surname,
                ];

                if (null === $data[$index]['journalid']) {
                    throw new Exception('Encryption failed for journalid');
                }

                if (null === $data[$index]['userid']) {
                    throw new Exception('Encryption failed for userid');
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
                'Location' => __METHOD__.'()',
                'Exception' => $e->__toString(),
            ]);
        } finally {
            return $data;
        }
    }

    public function callsLoad($params)
    {
        
        $calls = null;

        try {
            $calls['pagination'] = $this->encrypt->scramble('pagination', 'method', $params['userid']);

            $calls['paginationShared'] = $this->encrypt->scramble('paginationShared', 'method', $params['userid']);

            $calls['JournalList'] = $this->encrypt->scramble('Journal\JournalList\JournalList', 'controller', $params['userid']);

            $calls['create'] = $this->encrypt->scramble('create', 'method', $params['userid']);

            $calls['Journal'] = $this->encrypt->scramble('\Journal\Journal\Journal', 'controller', $params['userid']);

            $calls['loadJournal'] = $this->encrypt->scramble('load', 'method', $params['userid']);

            $calls['myJournalList'] = $this->encrypt->scramble('Journal\JournalList\JournalList', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['pagination']:
                    throw new Exception('Encryption failed of pagination');

                    break;

                case null === $calls['paginationShared']:
                    throw new Exception('Encryption failed of paginationShared');

                    break;

                case null === $calls['JournalList']:
                    throw new Exception('Encryption failed of JournalList');

                    break;

                case null === $calls['create']:
                    throw new Exception('Encryption failed of create');

                    break;

                case null === $calls['Journal']:
                    throw new Exception('Encryption failed of Journal');

                    break;

                    case null === $calls['loadJournal']:
                        throw new Exception('Encryption failed of loadJournal');

                        break;

                    case null === $calls['myJournalList']:
                        throw new Exception('Encryption failed of myJournalList');

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

    public function callsPagination($params)
    {
        
        $calls = null;

        try {
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            $calls['Journal'] = $this->encrypt->scramble('\Journal\Journal\Journal', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['show']:
                    throw new Exception('Encryption failed of show');

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