<?php

namespace Synaptic4U\Packages\Journal\Journal;

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

    public function create()
    {
        
        $template = [
            'data' => $this->data,
            'calls' => $this->calls,
        ];

        $this->result['html'] = $this->template->build('create', $template);

        $this->result['script'] = $this->template->build('create_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function show()
    {
        
        $template = [
            'details' => $this->data['details'],
            'datedon' => substr($this->data['details']['datedon'], 2, 14),
            'journal' => $this->data['journal'],
            'calls' => $this->calls,
            'formArray' => $this->params['formArray'][0],
            'userid' => $this->params['userid'],
        ];

        $template['details']['datedon'] = substr($template['details']['datedon'], 2, 14);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        $this->result['html'] = $this->template->build('show', $template);

        return $this->result;
    }

    public function edit()
    {
        
        $template = [
            'details' => $this->data['details'],
            'datedon' => substr($this->data['details']['datedon'], 2, 14),
            'journal' => $this->data['journal'],
            'calls' => $this->calls,
            'formArray' => $this->params['formArray'][0],
            'userid' => $this->params['userid'],
        ];

        $template['details']['datedon'] = substr($template['details']['datedon'], 2, 14);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        $this->result['html'] = $this->template->build('edit', $template);

        return $this->result;
    }

    public function created()
    {
        
        $template = [
            'datedon' => substr($this->data['datedon'], 2, 14),
            'journalid' => $this->data['journalid'],
            'calls' => $this->calls,
        ];

        $template['msg'] = $this->template->build('created', $template);

        $this->result['html'] = '';

        $this->result['script'] = $this->template->build('created_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function updated()
    {
        
        $template = [
            'lastid' => $this->data['lastid'],
            'calls' => $this->calls,
        ];

        $template['msg'] = $this->template->build('updated', $template);

        $this->result['html'] = '';

        $this->result['script'] = $this->template->build('updated_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function empty()
    {
        
        $template = [
            'cnt' => $this->data['cnt'],
            'calls' => $this->calls,
        ];

        $template['msg'] = $this->template->build('empty', $template);

        $this->result['html'] = '';

        $this->result['script'] = $this->template->build('empty_js', $template);

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
        new Log($msg);
    }
}