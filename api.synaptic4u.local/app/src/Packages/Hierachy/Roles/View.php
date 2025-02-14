<?php

namespace Synaptic4U\Packages\Hierachy\Roles;

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

    public function show()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'users' => $this->calls['users'],
            'show' => $this->calls['show'],
            'roles' => $this->calls['roles'],
            'edit' => $this->calls['edit'],
            'store' => $this->calls['store'],
            'toggle' => $this->calls['toggle'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'hierachyname' => $this->data['hierachyname'],
            'role' => (isset($this->data['role'])) ? $this->data['role'] : null,
            'rolesArray' => $this->data['roles'],
            'canedit' => 1,
            'roles_for_body_id' => 'collapseOrganizationRoles',
            'roles_for_form_id' => 'collapseOrganizationRolesForm',
        ];

        $this->result['html'] = $this->template->build('show', $template);

        $this->result['script'] = $this->template->build('show_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function toggle()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'roles' => $this->calls['roles'],
            'toggle' => $this->calls['toggle'],
            'edit' => $this->calls['edit'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'roleid' => $this->data['roleid'],
            'hierachyname' => $this->data['hierachyname'],
            'role' => $this->data['role'],
            'userid' => $this->data['userid'],
            'user' => $this->data['user'],
            'datedon' => $this->data['datedon'],
            'exclude' => $this->data['exclude'],
            'count' => $this->data['count'],
            'orgs' => (0 === (int) $this->data['count']) ? null : $this->data['orgs'],
            'rowcount' => (0 === (int) $this->data['count']) ? $this->data['rowcount'] : null,
            'canedit' => 1,
            'toggleid' => $this->params['formArray']['toggleid'],
            'roles_for_body_id' => 'collapseOrganizationRoles',
            'roles_for_form_id' => 'collapseOrganizationRolesForm',
        ];

        $template['msg'] = $this->template->build('toggle_msg', $template);

        $this->result['html'] = $this->template->build('toggle', $template);

        $this->result['script'] = $this->template->build('toggle_js', $template);

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
            'roles' => $this->calls['roles'],
            'show' => $this->calls['show'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'hierachyname' => $this->data['hierachyname'],
            'roleid' => $this->data['roleid'],
            'rowcount' => (isset($this->data['rowcount'])) ? $this->data['rowcount'] : 0,
            'role' => $this->data['role']['value'],
            'roles_for_body_id' => 'collapseOrganizationRoles',
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
            'roles' => $this->calls['roles'],
            'update' => $this->calls['update'],
            'delete' => $this->calls['delete'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'hierachyname' => $this->data['hierachyname'],
            'roleid' => (isset($this->data['role']['roleid'])) ? $this->data['role']['roleid'] : $this->data['roleid'],
            'role' => (isset($this->data['role']['role'])) ? $this->data['role']['role'] : $this->data['role'],
            'exclude' => (isset($this->data['role']['exclude'])) ? $this->data['role']['exclude'] : $this->data['exclude'],
            'checkbox_view' => (isset($this->data['role']['view'])) ? $this->data['role']['view'] : $this->data['view'],
            'checkbox_create' => (isset($this->data['role']['create'])) ? $this->data['role']['create'] : $this->data['create'],
            'checkbox_edit' => (isset($this->data['role']['edit'])) ? $this->data['role']['edit'] : $this->data['edit'],
            'checkbox_delete' => (isset($this->data['role']['delete'])) ? $this->data['role']['delete'] : $this->data['delete'],
            'user' => (isset($this->data['role']['user'])) ? $this->data['role']['user'] : $this->data['user'],
            'updatedon' => (isset($this->data['role']['updatedon'])) ? $this->data['role']['updatedon'] : $this->data['updatedon'],
            'canedit' => 1,
            'roles_for_body_id' => 'collapseOrganizationRoles',
            'roles_for_form_id' => 'collapseOrganizationRolesForm',
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
            'roles' => $this->calls['roles'],
            'show' => $this->calls['show'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'roleid' => $this->data['roleid'],
            'hierachyname' => $this->data['hierachyname'],
            'rowcount' => (isset($this->data['rowcount'])) ? $this->data['rowcount'] : 0,
            'hierachyroledefault' => $this->data['hierachyroledefault'],
            'orgs' => (isset($this->data['orgs'])) ? $this->data['orgs'] : null,
            'role' => $this->data['role']['value'],
            'roles_for_body_id' => 'collapseOrganizationRoles',
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
            'roles' => $this->calls['roles'],
            'toggle' => $this->calls['toggle'],
            'show' => $this->calls['show'],
            'edit' => $this->calls['edit'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'roleid' => $this->data['roleid'],
            'hierachyname' => $this->data['hierachyname'],
            'role' => $this->data['role'],
            'count' => $this->data['count'],
            'orgs' => (0 === (int) $this->data['count']) ? null : $this->data['orgs'],
            'rowcount' => (0 === (int) $this->data['count']) ? $this->data['rowcount'] : null,
            'canedit' => 1,
            'roles_for_body_id' => 'collapseOrganizationRolesBody',
            'roles_for_form_id' => 'collapseOrganizationRolesForm',
        ];

        $template['msg'] = $this->template->build('deleted', $template);

        $this->result['script'] = $this->template->build('deleted_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function getRoles($select, $roleid)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'roles' => $this->data,
            'roleid' => $roleid,
            'select' => $select,
        ];

        $this->result['html'] = $this->template->build('get_roles', $template);

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
