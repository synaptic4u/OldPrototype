<?php

namespace Synaptic4U\Packages\Hierachy\Hierachy;

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

    public function create()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $template = [
            'hierachy' => $this->calls['hierachy'],
            'store' => $this->calls['store'],
            'hierachyid' => (isset($this->params['formArray'][1]) && strlen($this->params['formArray'][1]) > 0) ? $this->params['formArray'][0] : $this->calls['hierachyid'],
            'hierachysubid' => (isset($this->params['formArray'][1]) && strlen($this->params['formArray'][1]) > 0) ? $this->params['formArray'][1] : $this->calls['hierachysubid'],
            'nested' => (isset($this->params['formArray'][2]) && strlen($this->params['formArray'][2]) > 0) ? $this->params['formArray'][2] : $this->calls['nested'],
            'delete' => $this->calls['delete'],
            'detail' => $this->calls['detail'],
            'select_hierachy_type' => $this->data['select']['html'],
            'hierachy_list' => 'hierachy-form',
            'hierachyname' => $this->data['hierachyname'],
            'hierachydescription' => $this->data['hierachydescription'],
        ];

        $this->result['html'] = $this->template->build('hierachy_org_form', $template);

        // $this->result['script'] = $this->template->build('load_js', $template);

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
            'hierachy' => $this->calls['hierachy'],
            'show' => $this->calls['show'],
            'hierachy_list' => 'hierachy-list',
            'hierachyName' => $this->data['hierachyname']['value'],
            'detid' => (isset($this->data['detid'])) ? $this->data['detid'] : null,
        ];

        $template['msg'] = $this->template->build('created', $template);

        $this->result['script'] = $this->template->build('created_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function getHierachyType()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $template = [
            'name' => 'hierachytypeid',
            'id' => 'select-hierachy-type',
            'data' => $this->data,
        ];

        $this->result['html'] = $this->template->build('hierachy_type', $template);

        // $this->result['script'] = $this->template->build('load_js', $template);

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
            'hierachy' => $this->calls['hierachy'],
            'edit' => $this->calls['edit'],
            'particulars' => $this->calls['particulars'],
            'show' => $this->calls['show'],
            'type' => $this->data['type'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'visible' => (1 === (int) $this->data['visible']) ? 'All descendant organizations can view parent organizations.' : 'All descendant organizations can not view parent organizations.',
            'hierachyname' => $this->data['org'],
            'hierachydescription' => $this->data['description'],
            'parentorg' => $this->data['parentorg'],
            'canedit' => 1,
            'hierachy_det_form_id' => 'collapseOrganizationDetailsFormBody',
        ];

        $this->result['html'] = $this->template->build('show', $template);

        $this->result['script'] = $this->template->build('show_js', $template);

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
            'hierachy' => $this->calls['hierachy'],
            'update' => $this->calls['update'],
            'delete' => $this->calls['delete'],
            'select_hierachy_type' => $this->data['selectHierachyType']['html'],
            'hierachyid' => $this->data['hierachyid'],
            'hierachysubid' => $this->data['hierachysubid'],
            'hierachydetid' => $this->data['hierachydetid'],
            'hierachyvisibility' => $this->data['hierachyvisibility'],
            'hierachyname' => $this->data['hierachyname'],
            'hierachydescription' => $this->data['hierachydescription'],
            'parentorg' => $this->data['parentorg'],
            'candelete' => 1,
            'hierachy_det_form_id' => 'collapseOrganizationDetailsFormBody',
        ];

        $this->result['html'] = $this->template->build('edit', $template);

        $this->result['script'] = $this->template->build('edit_js', $template);

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
            'hierachy' => $this->calls['hierachy'],
            'show' => $this->calls['show'],
            'subhierachy' => $this->calls['subhierachy'],
            'subshow' => $this->calls['subshow'],
            'hierachy_list' => 'hierachy-list',
            'hierachy_det_form_id' => 'collapseOrganizationDetailsFormBody',
            'hierachyname' => $this->data['hierachyname']['value'],
            'hierachycreator' => $this->data['hierachycreator'],
            'count' => ((int) $this->data['count'] > 0) ? $this->data['count'] : null,
            'countdet' => ((int) $this->data['countdet'] > 0) ? $this->data['countdet'] : null,
            'hierachyid' => $this->data['updated']['hierachyid'],
            'detid' => $this->data['updated']['detid'],
        ];

        $template['msg'] = $this->template->build('updated', $template);

        $this->result['script'] = $this->template->build('updated_js', $template);

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
