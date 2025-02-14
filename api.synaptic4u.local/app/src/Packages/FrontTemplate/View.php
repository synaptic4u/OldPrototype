<?php

namespace Synaptic4U\Packages\FrontTemplate;

use Exception;
use Synaptic4U\Core\Config;
use Synaptic4U\Core\Log;
use Synaptic4U\Core\Template;

class View
{
    protected $params = [];
    protected $result = [];
    protected $calls = [];
    protected $data = [];
    protected $template;
    protected $config;

    public function __construct($params, $data, $calls)
    {
        try {
            $this->result = [
                'html' => '',
                'script' => '',
            ];

            $this->params = $params;

            $this->data = $data;

            $this->calls = $calls;

            $this->template = new Template(__DIR__, 'Views');

            $this->config = (new Config())->getConfig();

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($this->params, JSON_PRETTY_PRINT),
                'data' => json_encode($this->data, JSON_PRETTY_PRINT),
                'calls' => json_encode($this->calls, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                'Error' => $e->__toString(),
            ]);
        }
    }

    public function show()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $company = (isset($this->data['hierachy'])) ? array_shift($this->data['hierachy']) : 0;

        // System Overview -> Core System Menu calls
        $template['config'] = $this->config;
        $template['loadSupport'] = $this->calls['loadSupport'];
        $template['Support'] = $this->calls['Support'];
        $template['loadBilling'] = $this->calls['loadBilling'];
        $template['Billing'] = $this->calls['Billing'];
        $template['loadHierachy'] = $this->calls['loadHierachy'];
        $template['Hierachy'] = $this->calls['Hierachy'];
        $template['loadSubscriber'] = $this->calls['loadSubscriber'];
        $template['Subscriber'] = $this->calls['Subscriber'];
        $template['loadNotifications'] = $this->calls['loadNotifications'];
        $template['Notifications'] = $this->calls['Notifications'];
        $template['loadDashboard'] = $this->calls['loadDashboard'];
        $template['Dashboard'] = $this->calls['Dashboard'];
        $template['loadApplications'] = $this->calls['loadApplications'];
        $template['Applications'] = $this->calls['Applications'];

        if ((int) $company['id'] > 0) {
            $template['hierachyname'] = (1 === (int) $this->params['mobile']) ? substr($company['name'], 0, 10) : substr($company['name'], 0, 30);
            $template['hierachylogo'] = $company['logo'];
        } else {
            $template['hierachyname'] = null;
        }

        // User information and User calls
        $template['userid'] = $this->data['user']['userid'];
        $template['name'] = $this->data['user']['name'];
        $template['profile'] = $this->data['user']['profile'];
        $template['show'] = $this->data['calls']['show'];
        $template['user1'] = $this->data['calls']['User1'];
        $template['logout'] = $this->data['calls']['logout'];
        $template['user2'] = $this->data['calls']['User2'];

        $this->result['html'] = $this->template->build('show', $template);

        $this->result['script'] = $this->template->build('show_js', $template);

        $this->log([
            'Location' => __METHOD__.'() - DEBUG LOG',
            'company' => json_encode([$company], JSON_PRETTY_PRINT),
            'template' => json_encode([$template], JSON_PRETTY_PRINT),
            'hierachyname' => (1 === (int) $this->params['mobile']) ? substr($company['name'], 0, 10) : substr($company['name'], 0, 30),
        ]);

        return $this->result;
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
