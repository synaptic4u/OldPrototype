<?php

namespace Synaptic4U\Packages\Journal\JournalList;

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

    public function pagination()
    {
        $template = [
            'data' => $this->data,
            'calls' => $this->calls,
        ];

        $this->result['html'] = $this->template->build('pagination', $template);

        $this->result['script'] = $this->template->build('pagination_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function showlist($pagination = 5)
    {
        $heading = null;
        $method = null;
        $button = null;
        $shared = null;
        $page = 0;

        if (isset($this->data['count']) && ((int) $this->data['count']) > 0) {
            $heading = 'Shared Journal List';

            $method = $this->calls['paginationShared'];

            $shared = array_shift($this->data);

            $userid = array_shift($this->data);
        } else {
            $heading = 'Journal for '.array_shift($this->data).' ';

            $method = $this->calls['pagination'];

            $userid = array_shift($this->data);
        }

        $pages = [];

        for ($cnt = 0; $cnt < sizeof($this->data); ++$cnt) {
            $x = $cnt + 1;

            if ((($x % $pagination) == 0) || ((($x % $pagination) / 1) == 1) || ($x == sizeof($this->data))) {
                if ((($x % $pagination) / 1) == 1) {
                    $pages[] = $this->data[$cnt]['datedon'];
                }

                if (($x % $pagination) == 0 || ($x == sizeof($this->data))) {
                    $pages[] = $this->data[$cnt]['datedon'];
                }
            }
        }
        $size = sizeof($pages) / 2;

        $template = [
            'data' => $this->data,
            'calls' => $this->calls,
            'heading' => $heading,
            'method' => $method,
            'button' => $button,
            'shared' => $shared,
            'page' => $page,
            'pages' => $pages,
            'size' => $size,
            'userid' => $userid,
        ];

        $this->result['html'] = $this->template->build('showlist', $template);

        $this->result['script'] = $this->template->build('showlist_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'result' => json_encode($this->result, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function empty()
    {
        $template = [
            'data' => $this->data,
            'calls' => $this->calls,
        ];

        $template['msg'] = $this->template->build('empty', $template);

        $this->result['script'] = $this->template->build('empty_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
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
