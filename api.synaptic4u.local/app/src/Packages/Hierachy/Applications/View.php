<?php

namespace Synaptic4U\Packages\Hierachy\Applications;

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
            'types' => $this->calls['types'],
            'show' => $this->calls['show'],
            'applications' => $this->calls['applications'],
            'store' => $this->calls['store'],
            'remove' => $this->calls['remove'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'hierachyname' => $this->data['hierachyname'],
            'selectApps' => (isset($this->data['selectApps']['availablecount'])) ? null : $this->data['selectApps'],
            'count' => $this->data['count'],
            'subscribedApps' => (isset($this->data['subscribedApps'])) ? $this->data['subscribedApps'] : null,
            'applications_for_body_id' => 'collapseOrganizationApplicationsDetails',
        ];

        $this->result['html'] = $this->template->build('show', $template);

        $this->result['script'] = $this->template->build('show_js', $template);

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
            'applications' => $this->calls['applications'],
            'show' => $this->calls['show'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'appname' => $this->data['appname'],
            'rowcount' => $this->data['rowcount'],
            'applications_for_body_id' => 'collapseOrganizationApplicationsDetails',
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
            'applications' => $this->calls['applications'],
            'show' => $this->calls['show'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'appname' => $this->data['appname'],
            'rowcount' => $this->data['rowcount'],
            'applications_for_body_id' => 'collapseOrganizationApplicationsDetails',
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
