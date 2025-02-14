<?php

namespace Synaptic4U\Packages\Hierachy\AppRoles;

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
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

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
            'approles' => $this->calls['approles'],
            'edit' => $this->calls['edit'],
            'hierachyid' => $this->data['hierachyid'],
            'hierachyname' => $this->data['hierachyname'],
            'apps' => $this->data['apps'],
            'count' => $this->data['count'],
            'canedit' => 1,
            'approles_for_body_id' => 'collapseHierachyApplicationsRolesBody',
            'approles_for_form_id' => 'collapseHierachyApplicationsRolesForm',
        ];

        $this->result['html'] = $this->template->build('show', $template);

        // $this->result['script'] = $this->template->build('show_js', $template);

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
            'approles' => $this->calls['approles'],
            'editDetail' => $this->calls['editDetail'],
            'hierachyid' => $this->data['hierachyid'],
            'appid' => $this->data['appid'],
            'hierachyname' => $this->data['hierachyname'],
            'app_name' => array_shift($this->data['app_roles']),
            'app_last_edited' => array_shift($this->data['app_roles']),
            'app_roles' => $this->data['app_roles'],
            'moduleid' => $this->data['app_roles'][0]['moduleid'],
            'moduleids' => $this->data['module_list'],
            'canedit' => 1,
            'approles_for_body_id' => 'collapseHierachyApplicationsRolesBody',
            'approles_for_form_id' => 'collapseHierachyApplicationsRolesForm',
            'approles_for_module_row_id' => 'app_roles_row_'.$this->data['app_roles'][0]['modid'],
        ];

        $this->result['html'] = $this->template->build('edit', $template);

        $this->result['script'] = $this->template->build('edit_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function editDetail()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'approles' => $this->calls['approles'],
            'editDetail' => $this->calls['editDetail'],
            'hierachyid' => $this->data['hierachyid'],
            'appid' => $this->data['appid'],
            'moduleid' => $this->data['moduleid'],
            'hierachyname' => $this->data['hierachyname'],
            'mod_roles' => $this->data['mod_roles'],
            'roles' => $this->data['roles'],
            'users' => $this->data['users'],
            'moduleids' => $this->data['moduleids'],
            'next_moduleid' => $this->data['next_moduleid'],
            'count' => $this->data['count'],
            'canedit' => 1,
            'app_roles_row_roles' => 'app_roles_row_roles_'.$this->data['moduleid']['id'],
            'app_roles_row_roles_form' => 'app_roles_row_roles_form_'.$this->data['moduleid']['id'],
            'approles_for_module_row_id' => 'app_roles_row_'.$this->data['next_moduleid']['id'],
        ];

        $this->result['html'] = $this->template->build('edit_detail', $template);

        $this->result['script'] = ('000' == $this->data['next_moduleid']) ? '' : $this->template->build('edit_detail_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function showAppRoles()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'approles' => $this->calls['approles'],
            'add' => $this->calls['storeAppRoles'],
            'remove' => $this->calls['removeAppRoles'],
            'hierachyid' => $this->data['hierachyid'],
            'appid' => $this->data['appid'],
            'moduleid' => $this->data['moduleid'],
            'hierachyname' => $this->data['hierachyname'],
            'mod_roles' => $this->data['mod_roles'],
            'roles' => $this->data['roles'],
            'count' => $this->data['count'],
            'canedit' => 1,
            'app_roles_row_roles' => 'app_roles_row_roles_'.$this->data['moduleid']['id'],
            'app_roles_row_roles_form' => 'app_roles_row_roles_form_'.$this->data['moduleid']['id'],
        ];

        $this->result['html'] = $this->template->build('show_app_roles', $template);

        // $this->result['script'] = $this->template->build('show_app_roles_js', $template);

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
            'approles' => $this->calls['approles'],
            'showAppRoles' => $this->calls['showAppRoles'],
            'hierachyid' => $this->data['hierachyid'],
            'moduleid' => $this->data['moduleid'],
            'appid' => $this->data['appid'],
            'role' => $this->data['role'],
            'rowcount' => $this->data['rowcount'],
            'app_roles_row_roles' => 'app_roles_row_roles_'.$this->data['moduleid']['id'],
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
            'approles' => $this->calls['approles'],
            'showAppRoles' => $this->calls['showAppRoles'],
            'hierachyid' => $this->data['hierachyid'],
            'moduleid' => $this->data['moduleid'],
            'appid' => $this->data['appid'],
            'role' => $this->data['role'],
            'rowcount' => $this->data['rowcount'],
            'app_roles_row_roles' => 'app_roles_row_roles_'.$this->data['moduleid']['id'],
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
