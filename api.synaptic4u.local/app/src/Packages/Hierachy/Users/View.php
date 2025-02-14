<?php

namespace Synaptic4U\Packages\Hierachy\Users;

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

    public function list()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'users' => $this->calls['users'],
            'page' => $this->calls['page'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'usersArray' => $this->data['usersList'],
            'canedit' => 1,
        ];

        $this->result['html'] = $this->template->build('list', $template);

        $this->result['script'] = $this->template->build('list_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function page()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'users' => $this->calls['users'],
            'edit' => $this->calls['edit'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'usersArray' => $this->data['users'],
            'canedit' => 1,
            'users_for_body_id' => 'collapseHierachyUsers',
            'users_for_form_id' => 'collapseHierachyUsersForm',
        ];

        $this->result['html'] = $this->template->build('page', $template);

        $this->result['script'] = $this->template->build('page_js', $template);

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
            'users' => $this->calls['users'],
            'store' => $this->calls['store'],
            'approles' => $this->calls['approles'],
            'show' => $this->calls['show'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'hierachyname' => $this->data['hierachyname'],
            'user' => (isset($this->data['user'])) ? $this->data['user'] : null,
            'usersArray' => $this->data['users'],
            'roles' => $this->data['roles'],
            'list' => $this->data['list']['html'],
            'list_js' => $this->data['list']['script'],
            'page' => $this->data['page']['html'],
            'canedit' => 1,
            'users_for_body_id' => 'collapseHierachyUsers',
            'users_for_form_id' => 'collapseHierachyUsersForm',
        ];

        $this->result['html'] = $this->template->build('show', $template);

        $this->result['script'] = $this->template->build('show_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function stored()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'users' => $this->calls['users'],
            'show' => $this->calls['show'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'hierachyname' => $this->data['hierachyname'],
            'exists' => $this->data['exists'],
            'inviteid' => $this->data['inviteid'],
            'hierachy_exists' => $this->data['hierachy_exists'],
            'invited' => $this->data['invited'],
            'noteid' => $this->data['noteid'],
            'userid' => $this->data['userid'],
            'firstname' => $this->data['firstname']['value'],
            'surname' => $this->data['surname']['value'],
            'personnel' => $this->data['personnel']['value'],
            'users_for_body_id' => 'collapseHierachyUsers',
        ];

        $template['msg'] = $this->template->build('created', $template);

        $this->result['script'] = $this->template->build('created_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function edit()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'users' => $this->calls['users'],
            'update' => $this->calls['update'],
            'delete' => $this->calls['delete'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'hierachyname' => $this->data['hierachyname'],
            'userid' => $this->data['user']['userid'],
            'firstname' => $this->data['user']['firstname'],
            'surname' => $this->data['user']['surname'],
            'email' => $this->data['user']['email'],
            'contactnu' => $this->data['user']['contactnu'],
            'maintainedby' => $this->data['user']['maintainedby'],
            'updatedon' => $this->data['user']['updatedon'],
            'personnel' => $this->data['user']['personnel'],
            'invite' => $this->data['user']['invite'],
            'roles' => $this->data['roles']['html'],
            'canedit' => 1,
            'users_for_body_id' => 'collapseHierachyUsers',
            'users_for_form_id' => 'collapseHierachyUsersForm',
        ];

        $this->result['html'] = $this->template->build('edit', $template);

        // $this->result['script'] = $this->template->build('edit_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function updated()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'users' => $this->calls['users'],
            'show' => $this->calls['show'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'userid' => $this->data['userid']['value'],
            'hierachyname' => $this->data['hierachyname'],
            'firstname' => $this->data['firstname']['value'],
            'surname' => $this->data['surname']['value'],
            'rowcount' => $this->data['rowcount'],
            'count' => $this->data['count'],
            'inviteid' => (isset($this->data['inviteid'])) ?: null,
            'noteid' => (isset($this->data['noteid'])) ?: null,
            'users_for_body_id' => 'collapseHierachyUsers',
        ];

        $template['msg'] = $this->template->build('updated', $template);

        $this->result['script'] = $this->template->build('updated_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function deleted()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'users' => $this->calls['users'],
            'show' => $this->calls['show'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'user' => $this->data['user'],
            'hierachyname' => $this->data['hierachyname'],
            'hierachy_users_count' => (isset($this->data['hierachy_users_count'])) ? $this->data['hierachy_users_count'] : null,
            'users_count' => (isset($this->data['users_count'])) ? $this->data['users_count'] : null,
            'users_for_body_id' => 'collapseHierachyUsers',
        ];

        $template['msg'] = $this->template->build('deleted', $template);

        $this->result['script'] = $this->template->build('deleted_js', $template);

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
