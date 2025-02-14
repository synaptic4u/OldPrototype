<?php

namespace Synaptic4U\Packages\Communication;

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
                'calls' => json_encode($this->calls, JSON_PRETTY_PRINT),
                'data' => json_encode($this->data, JSON_PRETTY_PRINT),
                'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                'Error' => $e->__toString(),
            ]);
        }
    }

    public function sendConfirmation()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $url = 'https://'.$this->params['url']['client'].'/index.php?';
        $url .= $this->calls['hashConfirmation'].'='.$this->calls['seclink'].'';
        $url .= '&'.$this->calls['hashConfirm'].'='.$this->calls['confirm'].'';
        $url .= '&'.$this->calls['hashUser'].'='.$this->calls['User'].'';

        $template = [
            'from_email' => $this->params['admin']['email'],
            'to_email' => $this->data['email'],
            'url' => $url,
        ];

        return ['email' => $this->template->build('confirm_email', $template), 'url' => $url];
    }

    public function sendInvite()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $url = 'https://'.$this->params['url']['client'].'/index.php?';
        $url .= $this->calls['hashConfirmation'].'='.$this->calls['seclink'].'';
        $url .= '&'.$this->calls['hashInvite'].'='.$this->calls['invite'].'';
        $url .= '&'.$this->calls['hashUser'].'='.$this->calls['User'].'';

        $template = [
            'from_userid' => $this->data['from_userid'],
            'to_userid' => $this->data['to_userid'],
            'from_email' => $this->data['from_email'],
            'to_email' => $this->data['to_email'],
            'from_user' => $this->data['from_user'],
            'to_user' => $this->data['to_user'],
            'application' => $this->data['application'],
            'hierachyname' => $this->data['hierachyname'],
            'url' => $url,
        ];

        $this->result['email'] = $this->template->build('invite_email', $template);

        $this->result['url'] = $url;

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
