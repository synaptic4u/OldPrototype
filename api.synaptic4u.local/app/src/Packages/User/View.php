<?php

namespace Synaptic4U\Packages\User;

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
                'calls' => json_encode($this->calls, JSON_PRETTY_PRINT),
                'params' => json_encode($this->params, JSON_PRETTY_PRINT),
                'data' => json_encode($this->data, JSON_PRETTY_PRINT),
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
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $template = [
            'firstname' => $this->data['firstname'],
            'surname' => $this->data['surname'],
            'email' => $this->data['email'],
            'contactnu' => $this->data['contactnu'],
            'passkey' => $this->data['passkey'],
            'popi_compliance' => $this->data['popi_compliance'],
            'store' => $this->calls['store'],
            'user' => $this->calls['user'],
            'email_exists' => $this->data['email_exists'],
        ];

        $this->result['html'] = $this->template->build('create', $template);

        $this->result['script'] = $this->template->build('create_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function invite()
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $template = [
            'inviteStore' => $this->calls['inviteStore'],
            'user' => $this->calls['User'],
            'hierachyid' => $this->data['hierachyid'],
            'detid' => $this->data['detid'],
            'userid' => $this->data['userid'],
            'firstname' => $this->data['firstname'],
            'surname' => $this->data['surname'],
            'email' => $this->data['email'],
            'contactnu' => $this->data['contactnu'],
            'passkey' => $this->data['passkey'],
            'popi_compliance' => $this->data['popi_compliance'],
            'org' => $this->data['org'],
            'invitedby' => $this->data['invitedby'],
        ];

        $this->result['html'] = $this->template->build('invite', $template);

        $this->result['script'] = $this->template->build('invite_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function inviteCreated()
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $name = $this->params['formArray']['firstname'].' '.$this->params['formArray']['surname'];

        $template = [
            'name' => $name,
            'userid' => $this->params['userid'],
            'login' => $this->calls['login'],
            'user' => $this->calls['User'],
            'calls_userid' => $this->calls['calls_userid'],
            'email' => $this->params['formArray']['email'],
            'password' => $this->params['formArray']['passkey'],
        ];

        $this->result['html'] = $this->template->build('invite_created', $template);

        $this->result['script'] = $this->template->build('invite_created_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function created()
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $name = $this->params['formArray']['firstname'].' '.$this->params['formArray']['surname'];

        if ($this->data['userid'] > 0) {
            $msg = $name.' was successfully created';
        } else {
            $msg = $name.' was NOT created';
        }

        $template = [
            'name' => $name,
            'data' => $this->data['userid'],
            'login' => $this->calls['login'],
            'user' => $this->calls['User'],
            'userid' => $this->calls['userid'],
            'email' => $this->params['formArray']['email'],
            'password' => $this->params['formArray']['passkey'],
            'msg' => $msg,
        ];

        $this->result['html'] = $this->template->build('created', $template);

        $this->result['script'] = $this->template->build('created_js', $template);

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        return $this->result;
    }

    public function show()
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $template = [
            'userid' => $this->data['userid'],
            'firstname' => $this->data['firstname'],
            'surname' => $this->data['surname'],
            'email' => $this->data['email'],
            'contactnu' => $this->data['contactnu'],
            'edit' => $this->calls['edit'],
            'user' => $this->calls['User'],
        ];

        $this->result['html'] = $this->template->build('show', $template);

        return $this->result;
    }

    public function edit()
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $template = [
            'userid' => $this->data['userid'],
            'firstname' => $this->data['firstname'],
            'surname' => $this->data['surname'],
            'email' => $this->data['email'],
            'contactnu' => $this->data['contactnu'],
            'passkey' => $this->data['passkey'],
            'update' => $this->calls['update'],
            'user' => $this->calls['User'],
            'delete' => $this->calls['delete'],
            'userd' => $this->calls['UserD'],
        ];

        $this->result['html'] = $this->template->build('edit', $template);

        return $this->result;
    }

    public function updated()
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $template = [
            'firstname' => $this->params['formArray']['firstname'],
            'surname' => $this->params['formArray']['surname'],
            'lastid' => $this->data['lastid'],
            'show' => $this->calls['show'],
            'user' => $this->calls['User'],
        ];

        $template['msg'] = $this->template->build('updated', $template);

        $this->result['html'] = '';

        $this->result['script'] = $this->template->build('updated_js', $template);

        return $this->result;
    }

    public function resend()
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $template = [
            'calls' => $this->calls,
        ];

        $this->log([
            'Location' => __METHOD__.'()',
            'template' => json_encode($template, JSON_PRETTY_PRINT),
        ]);

        $this->result['html'] = $this->template->build('resend', $template);

        return $this->result;
    }

    public function forgot()
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $template = [
            'data' => $this->data,
        ];

        $this->result['html'] = $this->template->build('forgot', $template);

        return $this->result;
    }

    public function updatePassword()
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $template = [
            'calls' => $this->calls,
        ];

        $this->result['html'] = $this->template->build('updatePassword', $template);

        $this->result['script'] = $this->template->build('updatePassword_js', $template);

        return $this->result;
    }

    public function storedPassword()
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);  
        $template = [
            'calls' => $this->calls,
            'data' => $this->data,
        ];

        $this->result['html'] = $this->template->build('storedPassword', $template);

        // $this->result['script'] = $this->template->build('storedPassword_js', $template);

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