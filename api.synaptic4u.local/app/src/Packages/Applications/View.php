<?php

namespace Synaptic4U\Packages\Applications;

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
                'data' => json_encode($this->data),
                'params' => json_encode($this->params),
                'calls' => json_encode($this->calls),
            ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                'Error' => $e->__toString(),
            ]);
        }
    }

    public function load()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        // Application information & calls
        // Personnel Applications
        if (!is_null($this->data['personnel_applications'])) {
            $template['personnel_applications'] = $this->data['personnel_applications']['data'];
            $template['personnel_applications_count'] = (isset($this->data['personnel_applications']['count'])) ? $this->data['personnel_applications']['count'] : sizeof($this->data['personnel_applications']['apps']);

            $this->result['html'] .= $this->template->build('personnel_apps_show', $template);
        }

        // Organization Applications
        if (!is_null($this->data['orgnization_applications'])) {
            $template['orgnization_applications'] = $this->data['orgnization_applications']['data'];
            $template['orgnization_applications_count'] = (isset($this->data['orgnization_applications']['count'])) ? $this->data['orgnization_applications']['count'] : sizeof($this->data['orgnization_applications']['apps']);

            $this->result['html'] .= $this->template->build('organization_apps_show', $template);
        }

        $this->log([
            'Location' => __METHOD__.'() - DEBUG LOG',
            'template' => json_encode([$template], JSON_PRETTY_PRINT),
        ]);

        $this->result['script'] = $this->template->build('show_js', $template);

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
