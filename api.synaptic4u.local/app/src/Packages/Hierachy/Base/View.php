<?php

namespace Synaptic4U\Packages\Hierachy\Base;

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

    public function load()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        if (isset($this->data['count']) && $this->data['count'] > 0) {
            $template = [
                'controller' => $this->calls['hierachy'],
                'method' => $this->calls['show'],
                'id' => 'hierachy-list',
            ];
        } else {
            $template = [
                'controller' => $this->calls['hierachy'],
                'method' => $this->calls['create'],
                'id' => 'hierachy-form',
            ];
        }

        $this->result['html'] = $this->template->build('load', $template);

        $this->result['script'] = $this->template->build('load_js', $template);

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
        $template['hierachy'] = $this->data;
        $template['calls'] = $this->calls;

        $ids = array_column($template['hierachy'], 'id');
        $subids = array_column($template['hierachy'], 'subid');

        for ($x = 0; $x < sizeof($template['hierachy']); ++$x) {
            if ((0 === (int) $template['hierachy'][$x]['levelid']) && ((int) $template['hierachy'][$x]['subid'] > 0)) {
                $template['hierachy'][$x]['html'] = $this->template->build('nested', ['calls' => $template['calls'], 'hierachy' => $template['hierachy'][$x]]);
                $template['hierachy'][array_search($subids[$x], $ids)]['ids']['"'.$template['hierachy'][$x]['name'].'"'] = $template['hierachy'][$x]['html'];
            }
        }

        for ($x = sizeof($template['hierachy']) - 1; $x >= 0; --$x) {
            if ((int) $template['hierachy'][$x]['levelid'] > 0) {
                sort($template['hierachy'][$x]['ids']);
                $template['hierachy'][$x]['html'] = $this->template->build('nester', ['calls' => $template['calls'], 'hierachy' => $template['hierachy'][$x]]);
                $template['hierachy'][array_search($subids[$x], $ids)]['ids']['"'.$template['hierachy'][$x]['name'].'"'] = (in_array($subids[$x], $ids)) ? $template['hierachy'][$x]['html'] : '';
            }
            if (0 === (int) $template['hierachy'][$x]['levelid'] && 0 === (int) $template['hierachy'][$x]['subid']) {
                $template['hierachy'][$x]['html'] = $this->template->build('nested', ['calls' => $template['calls'], 'hierachy' => $template['hierachy'][$x]]);
            }
        }
        for ($x = 0; $x < sizeof($template['hierachy']); ++$x) {
            if (0 === (int) $template['hierachy'][$x]['subid']) {
                $this->result['html'] .= $template['hierachy'][$x]['html'];
            }
        }

        $this->result['script'] = $this->template->build('nest_js', $template['calls']);

        $this->log([
            'Location' => __METHOD__.'()',
            'ids' => json_encode($ids, JSON_PRETTY_PRINT),
            'subids' => json_encode($subids, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function detail()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $template = [
            'subhierachy' => $this->calls['subhierachy'],
            'subshow' => $this->calls['subshow'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'hierachycreator' => $this->data['hierachycreator'],
            'hierachyname' => $this->data['org'],
        ];

        $this->result['html'] = $this->template->build('hierachy_org_detail', $template);

        $this->result['script'] = $this->template->build('hierachy_org_detail_js', $template);

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
