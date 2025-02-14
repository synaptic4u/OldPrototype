<?php

namespace Synaptic4U\Packages\Journal\Checklist;

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
                'result' => json_encode($this->result),
                'params' => json_encode($this->params),
                'data' => json_encode($this->data),
                'calls' => json_encode($this->calls),
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

    public function create()
    {
        
        $template = [
            'calls' => $this->calls,
        ];

        $this->result['html'] = $this->template->build('create', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
            'result' => json_encode($this->result, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function created()
    {
        
        $template = [
            'calls' => $this->calls,
            'checklistid' => $this->data['checklistid'],
            'datedon' => (isset($this->data['datedon'])) ? $this->data['datedon'] : '',
        ];

        $template['msg'] = $this->template->build('created', $template);

        $this->result['script'] = $this->template->build('created_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
            'result' => json_encode($this->result, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function show()
    {
        
        $template = [
            'calls' => $this->calls,
            'data' => $this->data,
        ];

        $this->result['html'] = $this->template->build('show', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
            'result' => json_encode($this->result, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function edit()
    {
        
        $template = [
            'calls' => $this->calls,
            'data' => $this->data,
        ];

        $this->result['html'] = $this->template->build('edit', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
            'result' => json_encode($this->result, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function updated()
    {
        
        $template = [
            'calls' => $this->calls,
            'checklistid' => $this->data['checklistid'],
            'datedon' => (isset($this->data['datedon'])) ? $this->data['datedon'] : '',
        ];

        $template['msg'] = $this->template->build('updated', $template);

        $this->result['script'] = $this->template->build('updated_js', $template);

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