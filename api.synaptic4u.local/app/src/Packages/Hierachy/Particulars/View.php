<?php

namespace Synaptic4U\Packages\Hierachy\Particulars;

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

    public function getPartUsers()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'name' => 'contactuserid',
            'id' => 'select-contact-user',
            'selectUser' => $this->data,
        ];

        // $this->result['html'] = $this->template->build('select_users', $template);

        // $this->result['script'] = $this->template->build('load_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->template->build('select_users', $template);
        // return $this->result;
    }

    public function create()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'particulars' => $this->calls['particulars'],
            'store' => $this->calls['store'],
            'applications' => $this->calls['applications'],
            'show' => $this->calls['show'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'particularid' => $this->data['particularid'],
            'imageid' => $this->data['imageid'],
            'hierachyname' => $this->data['hierachyname'],
            'contactuser' => $this->data['contactuser'],
            'phone' => $this->data['phone'],
            'address' => $this->data['address'],
            'ispostal' => $this->data['ispostal'],
            'website' => $this->data['website'],
            'logo' => $this->data['logo'],
            'postal' => $this->data['postal'],
            'canedit' => 1,
            'config' => $this->config,
            'particulars_for_body_id' => 'collapseOrganizationParticularsFormBody',
            'selectUser' => $this->data['selectUser'],
        ];

        $this->result['html'] = $this->template->build('create', $template);

        $this->result['script'] = $this->template->build('create_js', $template);

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
            'particulars' => $this->calls['particulars'],
            'show' => $this->calls['show'],
            'particulars_for_body_id' => 'collapseOrganizationParticularsFormBody',
            'hierachyName' => $this->data['hierachyname']['value'],
            'particularid' => (isset($this->data['particularid']['id'])) ? $this->data['particularid']['id'] : null,
            'imageid' => (isset($this->data['imageid'])) ? $this->data['imageid'] : null,
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
        ];

        $template['msg'] = $this->template->build('created', $template);

        $this->result['script'] = $this->template->build('created_js', $template);

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
            'particulars' => $this->calls['particulars'],
            'edit' => $this->calls['edit'],
            'applications' => $this->calls['applications'],
            'show' => $this->calls['show'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'particularid' => $this->data['particularid'],
            'hierachyname' => $this->data['hierachyname'],
            'contactuser' => $this->data['contactuser'],
            'phone' => $this->data['phone'],
            'address' => $this->data['address'],
            'ispostal' => $this->data['ispostal'],
            'website' => $this->data['website'],
            'logo' => $this->data['logo'],
            'postal' => $this->data['postal'],
            'canedit' => 1,
            'config' => $this->config,
            'particulars_for_body_id' => 'collapseOrganizationParticularsFormBody',
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
            'particulars' => $this->calls['particulars'],
            'update' => $this->calls['update'],
            'delete' => $this->calls['delete'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'particularid' => $this->data['particularid'],
            'imageid' => $this->data['imageid'],
            'hierachyname' => $this->data['hierachyname'],
            'phone' => $this->data['phone'],
            'address' => $this->data['address'],
            'ispostal' => $this->data['ispostal'],
            'website' => $this->data['website'],
            'logo' => $this->data['logo'],
            'postal' => $this->data['postal'],
            'candelete' => 1,
            'config' => $this->config,
            'particulars_for_body_id' => 'collapseOrganizationParticularsFormBody',
            'selectUser' => $this->data['selectUser'],
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
            'particulars' => $this->calls['particulars'],
            'show' => $this->calls['show'],
            'particulars_for_body_id' => 'collapseOrganizationParticularsFormBody',
            'hierachyName' => $this->data['hierachyname']['value'],
            'particularid' => (isset($this->data['particularid']['id'])) ? $this->data['particularid']['id'] : null,
            'imageid' => (isset($this->data['imageid'])) ? $this->data['imageid'] : null,
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'status' => $this->data['status'],
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
