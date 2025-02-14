<?php

namespace Synaptic4U\Packages\Journal\ConfigNotifications;

use Exception;
use Synaptic4U\Core\Log;
use Synaptic4U\Core\Template;

class View
{
    protected $result = [];
    protected $params = [];
    protected $data = [];
    protected $calls = [];
    protected $template;

    public function __construct($params, $data = [], $calls = [])
    {
        
        try {
            $this->result = ['html' => '', 'script' => ''];

            $this->params = $params;

            $this->data = $data;

            $this->calls = $calls;

            $this->template = new Template(__DIR__, 'Views');

            $this->log([
                'Location' => __METHOD__.'()',
                'result' => json_encode($this->result, JSON_PRETTY_PRINT),
                'params' => json_encode($this->params, JSON_PRETTY_PRINT),
                'data' => json_encode($this->data, JSON_PRETTY_PRINT),
                'calls' => json_encode($this->calls, JSON_PRETTY_PRINT),
                '__DIR__' => __DIR__,
                'template' => serialize($this->template),
            ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                'Error' => $e->__toString(),
            ]);
        }
    }

    public function loadRequests()
    {
        
        array_shift($this->data);

        $template = [
            'calls' => $this->calls,
            'data' => $this->data,
        ];

        $this->result['html'] = $this->template->build('loadRequests', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
            'result' => json_encode($this->result, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function noRequests()
    {
        
        $template = [
            'calls' => $this->calls,
            'data' => $this->data,
        ];

        $this->result['html'] = $this->template->build('noRequests', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
            'result' => json_encode($this->result, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function acceptedRequest()
    {
        
        $template = [
            'calls' => $this->calls,
            'lastid' => $this->data['lastid'],
            'user' => $this->data['user'][0].' '.$this->data['user'][1],
        ];

        $template['msg'] = $this->template->build('acceptedRequest', $template);

        $this->result['script'] = $this->template->build('acceptedRequest_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
            'result' => json_encode($this->result, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    protected function error($msg)
    {
        new Log($msg, 'error');
    }

    protected function log($msg)
    {
        new Log($msg);
    }
}