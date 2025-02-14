<?php

namespace Synaptic4U\Packages\Journal\Dashboard;

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

    public function callsLoad($params)
    {

        $calls = null;

        try {
            $calls['loadlist'] = $this->encrypt->scramble('loadlist', 'method', $params['userid']);

            $calls['ConfigJournal'] = $this->encrypt->scramble('Journal\ConfigJournal\ConfigJournal', 'controller', $params['userid']);

            $calls['loadRequests'] = $this->encrypt->scramble('loadRequests', 'method', $params['userid']);

            $calls['ConfigNotifications'] = $this->encrypt->scramble('Journal\ConfigNotifications\ConfigNotifications', 'controller', $params['userid']);

            $calls['loadShareable'] = $this->encrypt->scramble('loadShareable', 'method', $params['userid']);

            $calls['ConfigSharing'] = $this->encrypt->scramble('Journal\ConfigSharing\ConfigSharing', 'controller', $params['userid']);

            switch (true) {
                case null === $calls['loadlist']:
                    throw new Exception('Encryption failed of loadlist');

                    break;

                case null === $calls['ConfigJournal']:
                    throw new Exception('Encryption failed of ConfigJournal');

                    break;

                case null === $calls['loadRequests']:
                    throw new Exception('Encryption failed of loadRequests');

                    break;

                case null === $calls['ConfigNotifications']:
                    throw new Exception('Encryption failed of ConfigNotifications');

                    break;

                case null === $calls['loadShareable']:
                    throw new Exception('Encryption failed of loadShareable');

                    break;

                case null === $calls['ConfigSharing']:
                    throw new Exception('Encryption failed of ConfigSharing');

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