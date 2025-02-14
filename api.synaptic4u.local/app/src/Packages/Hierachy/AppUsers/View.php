<?php

namespace Synaptic4U\Packages\Hierachy\AppUsers;

use Exception;
use Synaptic4U\Core\Log;
use Synaptic4U\Core\Template;

class View
{
    protected $params = [];
    protected $result = [];
    protected $calls = [];
    protected $data = [];
    protected $template;

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

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($this->params, JSON_PRETTY_PRINT),
                'calls' => json_encode($this->calls, JSON_PRETTY_PRINT),
                'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                'Error' => $e->__toString(),
            ]);
        }
    }

    public function getUsers()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'hierachyid' => $this->data['hierachyid'],
            'users' => $this->data['users'],
            'count' => $this->data['count'],
            // 'applications_for_body_id' => 'collapseOrganizationApplicationsDetails'
        ];

        $this->result['html'] = $this->template->build('getUsers', $template);

        // $this->result['script'] = $this->template->build('getUsers_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function show()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'appusers' => $this->calls['appusers'],
            'store' => $this->calls['store'],
            'remove' => $this->calls['remove'],
            'hierachyid' => $this->data['hierachyid'],
            'appid' => $this->data['appid'],
            'moduleid' => $this->data['moduleid'],
            'user_select' => $this->data['user_select'],
            'users' => $this->data['users'],
            'count' => $this->data['count'],
            'app_roles_row_user' => 'app_roles_row_user_'.$this->data['moduleid']['id'],
            'app_roles_row_user_form' => 'app_roles_row_user_form_'.$this->data['moduleid']['id'],
        ];

        $this->result['html'] = $this->template->build('show', $template);

        // $this->result['script'] = $this->template->build('show_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function created()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'appusers' => $this->calls['appusers'],
            'show' => $this->calls['show'],
            'hierachyid' => $this->data['hierachyid'],
            'appid' => $this->data['appid'],
            'moduleid' => $this->data['moduleid'],
            'userid' => $this->data['userid'],
            'user' => $this->data['user'],
            'rowcount' => $this->data['rowcount'],
            'count' => $this->data['count'],
            'include_exclude' => $this->data['include_exclude']['value'],
            'app_roles_row_user' => 'app_roles_row_user_'.$this->data['moduleid']['id'],
        ];

        $template['msg'] = $this->template->build('created', $template);

        $this->result['script'] = $this->template->build('created_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function removed()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'appusers' => $this->calls['appusers'],
            'show' => $this->calls['show'],
            'hierachyid' => $this->data['hierachyid'],
            'appid' => $this->data['appid'],
            'moduleid' => $this->data['moduleid'],
            'userid' => $this->data['userid'],
            'user' => $this->data['user'],
            'rowcount' => $this->data['rowcount'],
            'count' => $this->data['count'],
            'app_roles_row_user' => 'app_roles_row_user_'.$this->data['moduleid']['id'],
        ];

        $template['msg'] = $this->template->build('removed', $template);

        $this->result['script'] = $this->template->build('removed_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
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
