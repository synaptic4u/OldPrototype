<?php

namespace Synaptic4U\Packages\Journal\ConfigSharing;

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

    public function shareable()
    {
        
        $template = [
            'enable' => (0 === ((int) $this->data['sharing'])) ? '' : 'checked',
            'disable' => (1 === ((int) $this->data['sharing'])) ? '' : 'checked',
            'calls' => $this->calls,
        ];

        $this->result['html'] = $this->template->build('shareable', $template);

        $this->result['script'] = $this->template->build('shareable_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
            'result' => json_encode($this->result, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function shared()
    {
        
        array_shift($this->data);

        $template = [
            'data' => $this->data,
            'calls' => $this->calls,
        ];

        $this->result['html'] = $this->template->build('shared', $template);

        $this->result['script'] = $this->template->build('shared_js', $template);

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
            'sharing' => (0 === ((int) $this->data['sharing'])) ? 'Disabled' : 'Enabled',
            'data' => $this->data,
            'calls' => $this->calls,
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

    public function noShares()
    {
        
        $template = [
            'count' => $this->data['count'],
            'calls' => $this->calls,
        ];

        $this->result['html'] = $this->template->build('noShares', $template);

        $this->result['script'] = $this->template->build('noShares_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
            'result' => json_encode($this->result, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function requested()
    {
        
        $template = [
            'lastid' => $this->data['lastid'],
            'calls' => $this->calls,
        ];

        $template['msg'] = $this->template->build('requested', $template);

        $this->result['script'] = $this->template->build('requested_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
            'result' => json_encode($this->result, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function following()
    {
        
        $template = [
            'following' => array_shift($this->data),
            'data' => $this->data,
            'calls' => $this->calls,
        ];

        $this->result['html'] = $this->template->build('following', $template);

        $this->result['script'] = $this->template->build('following_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
            'result' => json_encode($this->result, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function noFollowing()
    {
        
        $template = [
            'following' => $this->data['following'],
            'calls' => $this->calls,
        ];

        $this->result['html'] = $this->template->build('noFollowing', $template);

        $this->result['script'] = $this->template->build('noFollowing_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
            'result' => json_encode($this->result, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function unfollowed()
    {
        
        $template = [
            'lastid' => $this->data['lastid'],
            'user' => $this->data['user'][0].' '.$this->data['user'][1],
            'calls' => $this->calls,
        ];

        $template['msg'] = $this->template->build('unfollowed', $template);

        $this->result['script'] = $this->template->build('unfollowed_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
            'result' => json_encode($this->result, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function followed()
    {
        
        array_shift($this->data);

        $template = [
            'data' => $this->data,
            'calls' => $this->calls,
        ];

        $this->result['html'] = $this->template->build('followed', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
            'result' => json_encode($this->result, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function notFollowed()
    {
        
        $template = [
            'followed' => $this->data['followed'],
            'calls' => $this->calls,
        ];

        $this->result['html'] = $this->template->build('notFollowed', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            'data' => json_encode($this->data, JSON_PRETTY_PRINT),
            'template' => json_encode($template, JSON_PRETTY_PRINT),
            'result' => json_encode($this->result, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function removedFollow()
    {
        
        $template = [
            'lastid' => $this->data['lastid'],
            'user' => $this->data['user'][0].' '.$this->data['user'][1],
            'calls' => $this->calls,
        ];

        $template['msg'] = $this->template->build('removedFollow', $template);

        $this->result['script'] = $this->template->build('removedFollow_js', $template);

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