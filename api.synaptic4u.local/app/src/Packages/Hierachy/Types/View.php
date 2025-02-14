<?php

namespace Synaptic4U\Packages\Hierachy\Types;

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
            'roles' => $this->calls['roles'],
            'show' => $this->calls['show'],
            'types' => $this->calls['types'],
            'edit' => $this->calls['edit'],
            'store' => $this->calls['store'],
            'toggle' => $this->calls['toggle'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'hierachyname' => $this->data['hierachyname'],
            'type' => (isset($this->data['type'])) ? $this->data['type'] : null,
            'typesArray' => $this->data['types'],
            'canedit' => 1,
            'types_for_body_id' => 'collapseOrganizationTypesBody',
            'types_for_form_id' => 'collapseOrganizationTypesForm',
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
            'types' => $this->calls['types'],
            'toggle' => $this->calls['toggle'],
            'edit' => $this->calls['edit'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'hierachytypeid' => $this->data['hierachytypeid'],
            'hierachyname' => $this->data['hierachyname'],
            'type' => $this->data['type'],
            'user' => $this->data['user'],
            'userid' => $this->data['userid'],
            'datedon' => $this->data['datedon'],
            'exclude' => $this->data['exclude'],
            'count' => $this->data['count'],
            'orgs' => (0 === (int) $this->data['count']) ? null : $this->data['orgs'],
            'rowcount' => (0 === (int) $this->data['count']) ? $this->data['rowcount'] : null,
            'canedit' => 1,
            'toggleid' => $this->params['formArray']['toggleid'],
            'types_for_body_id' => 'collapseOrganizationTypesBody',
            'types_for_form_id' => 'collapseOrganizationTypesForm',
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
            'types' => $this->calls['types'],
            'show' => $this->calls['show'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'hierachyname' => $this->data['hierachyname'],
            'hierachytypeid' => $this->data['hierachytypeid'],
            'rowcount' => (isset($this->data['rowcount'])) ? $this->data['rowcount'] : 0,
            'hierachytypedefault' => $this->data['hierachytypedefault'],
            'orgs' => (isset($this->data['orgs'])) ? $this->data['orgs'] : null,
            'type' => $this->data['type']['value'],
            'types_for_body_id' => 'collapseOrganizationTypesBody',
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
            'types' => $this->calls['types'],
            'update' => $this->calls['update'],
            'delete' => $this->calls['delete'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'hierachyname' => $this->data['hierachyname'],
            'hierachytypeid' => $this->data['hierachytypeid'],
            'type' => $this->data['type'],
            'exclude' => $this->data['exclude'],
            'user' => $this->data['user'],
            'updatedon' => $this->data['updatedon'],
            'canedit' => 1,
            'types_for_body_id' => 'collapseOrganizationTypesBody',
            'types_for_form_id' => 'collapseOrganizationTypesForm',
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
            'types' => $this->calls['types'],
            'show' => $this->calls['show'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'hierachyname' => $this->data['hierachyname'],
            'hierachytypeid' => $this->data['hierachytypeid'],
            'rowcount' => (isset($this->data['rowcount'])) ? $this->data['rowcount'] : 0,
            'hierachytypedefault' => $this->data['hierachytypedefault'],
            'orgs' => (isset($this->data['orgs'])) ? $this->data['orgs'] : null,
            'type' => $this->data['type']['value'],
            'types_for_body_id' => 'collapseOrganizationTypesBody',
        ];

        $template['msg'] = $this->template->build('created', $template);

        $this->result['script'] = $this->template->build('created_js', $template);

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
            'types' => $this->calls['types'],
            'toggle' => $this->calls['toggle'],
            'show' => $this->calls['show'],
            'edit' => $this->calls['edit'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'hierachytypeid' => $this->data['hierachytypeid'],
            'hierachyname' => $this->data['hierachyname'],
            'type' => $this->data['type'],
            'count' => $this->data['count'],
            'orgs' => (0 === (int) $this->data['count']) ? null : $this->data['orgs'],
            'rowcount' => (0 === (int) $this->data['count']) ? $this->data['rowcount'] : null,
            'canedit' => 1,
            'types_for_body_id' => 'collapseOrganizationTypesBody',
            'types_for_form_id' => 'collapseOrganizationTypesForm',
        ];

        $template['msg'] = $this->template->build('deleted', $template);

        $this->result['script'] = $this->template->build('deleted_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function getTypes()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'types' => $this->data['types'],
        ];

        $template['msg'] = $this->template->build('created', $template);

        $this->result['script'] = $this->template->build('created_js', $template);

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
