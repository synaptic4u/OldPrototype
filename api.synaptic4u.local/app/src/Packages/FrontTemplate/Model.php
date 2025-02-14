<?php

namespace Synaptic4U\Packages\FrontTemplate;

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

    public function callsSystemOverview($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $calls = null;

        try {
            $calls['loadSupport'] = $this->encrypt->scramble('load', 'method', $params['userid']);

            $calls['Support'] = $this->encrypt->scramble('Support\Support', 'controller', $params['userid']);

            $calls['loadBilling'] = $this->encrypt->scramble('load', 'method', $params['userid']);

            $calls['Billing'] = $this->encrypt->scramble('Accounts\Billing\Billing', 'controller', $params['userid']);

            $calls['loadHierachy'] = $this->encrypt->scramble('load', 'method', $params['userid']);

            $calls['Hierachy'] = $this->encrypt->scramble('Hierachy\Base\Hierachy', 'controller', $params['userid']);

            $calls['loadSubscriber'] = $this->encrypt->scramble('load', 'method', $params['userid']);

            $calls['Subscriber'] = $this->encrypt->scramble('Accounts\Subscriber\Subscriber', 'controller', $params['userid']);

            $calls['loadNotifications'] = $this->encrypt->scramble('load', 'method', $params['userid']);

            $calls['Notifications'] = $this->encrypt->scramble('Overview\Notifications\Notifications', 'controller', $params['userid']);

            $calls['loadDashboard'] = $this->encrypt->scramble('load', 'method', $params['userid']);

            $calls['Dashboard'] = $this->encrypt->scramble('Overview\Dashboard\Dashboard', 'controller', $params['userid']);

            $calls['loadApplications'] = $this->encrypt->scramble('load', 'method', $params['userid']);

            $calls['Applications'] = $this->encrypt->scramble('Applications\Applications', 'controller', $params['userid']);

            foreach ($calls as $key => $call) {
                if (null === $call) {
                    throw new Exception('Encryption for '.$key);
                }
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
            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'calls' => json_encode($calls, JSON_PRETTY_PRINT),
            // ]);

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
