<?php

namespace Synaptic4U\Packages\User;

use Synaptic4U\Core\Config;
use Synaptic4U\Core\Log;
use Synaptic4U\Core\Security;
use Synaptic4U\Packages\Mailer\Mailer;

class User
{
    public function create($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);

        $data = null;

        $mod = new Model();

        $data = $mod->create($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsCreate($params);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, $data, $calls);

        if (null === $view) {
            return null;
        }

        return $view->create();
    }

    public function store(&$params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
        
        $data = null;

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->store($params);

        if (($data['return'] > 0) || ($data['email_exists'] > 0)) {
            $calls = $mod->callsCreate($params);

            if (null === $calls) {
                return null;
            }

            $view = new View($params, $data, $calls);

            if (null === $view) {
                return null;
            }

            return $view->create();
        }

        $calls = $mod->callsStore($params);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, $data, $calls);

        if (null === $view) {
            return null;
        }

        return $view->created();
    }

    public function show($params)
    { 
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
        
        $data = null;

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->retrieve($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsShow($params);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, $data, $calls);

        if (null === $view) {
            return null;
        }

        return $view->show();
    }

    public function edit($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
        
        $data = null;

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->retrieve($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsEdit($params);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, $data, $calls);

        if (null === $view) {
            return null;
        }

        return $view->edit();
    }

    public function update($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
        
        $data = null;

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data['lastid'] = $mod->update($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsUpdate($params);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, $data, $calls);

        if (null === $view) {
            return null;
        }

        return $view->updated();
    }

    public function getUser($params, $calls = 0, $userid = 0)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
        
        $data = null;

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        if((int)$userid > 0){
            return $mod->getUser($params, $userid);
        }

        // Retrieves Name, Surname of user
        $result['user'] = $mod->getUser($params, $userid);

        if (null === $result['user']) {
            return null;
        }

        if((int)$calls === 1){
            $result['calls'] = $mod->callsLoggedIn($params);

            if (null === $result['calls']) {
                return null;
            }
        }        

        return $result;
    }

    public function logout($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
        
        $sec = null;

        $sec = new Security();

        if (null === $sec) {
            return null;
        }

        return $sec->exitApp([
            'userid' => $params['userid'],
            'msg' => 'Thank you.<br>You have logged out.',
        ]);
    }

    public function resend($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
        
        $data = null;

        $mod = new Model();

        $calls = $mod->callsResend($params);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, [], $calls);

        if (null === $view) {
            return null;
        }

        return $view->resend();
    }

    public function forgot($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
        
        $data = null;

        $sec = new Security();

        if (null === $sec) {
            return null;
        }

        $data = $sec->forgotPassword($params['formArray']['email']);

        if (null === $data) {
            return $sec->exitApp(0, "We couldn't find that email address in our system.");
        }

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $calls = $mod->callsforgot();

        if (null === $calls) {
            return null;
        }

        $config = new Config();

        $config_array = $config->getConfig();

        $mail = new Mailer();

        $url = null;
        $url .= 'https://'.$config_array['url']['client'].'/index.php?';
        $url .= ''.$calls['ParamNameconfirmation'].'='.$data.'';
        $url .= '&'.$calls['ParamNameconfirm'].'='.$calls['updatePassword'].'';
        $url .= '&'.$calls['ParamNameUser'].'='.$calls['User'].'';

        // $this->log([
        //     'Location' => __METHOD__.'(): 3',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'params[formArray][0]' => $params['formArray'][0],
        //     'data' => $data,
        //     'mail' => serialize($mail),
        //     'calls' => json_encode($calls, JSON_PRETTY_PRINT),
        // ]);

        $result = $mail->sendSecLink($params['formArray']['email'], $url);

        $data = [
            'result' => $result,
            'email' => $params['formArray']['email'],
        ];

        $view = new View($params, $data, $calls);

        if (null === $view) {
            return null;
        }

        $msg = $view->forgot();

        return $sec->exitApp(['userid' => -100, 'msg' => $msg['html']]);
    }

    public function updatePassword($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
        
        $data = null;

        $sec = new Security();

        if (null === $sec) {
            return null;
        }

        $data = $sec->updatePassword($params['formArray']);

        if (isset($data['error'])) {
            return $sec->exitApp(0, $data['error']);
        }

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $params['userid'] = (isset($data['userid']) && -100 != $data['userid']) ? $data['userid'] : -100;

        $calls = $mod->callsupdatePassword($params);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, $data, $calls);

        if (null === $view) {
            return null;
        }

        return $view->updatePassword();
    }

    public function storePassword(&$params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
        
        $data = null;

        $mod = new Model();

        if (null === $mod) {
            return null;
        }

        $data = $mod->storePassword($params);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsstorePassword($params);

        if (null === $calls) {
            return null;
        }

        $sec = new Security();

        if (null === $sec) {
            return null;
        }
        $msg = $sec->sendConfirmation($data['email'], $data['userid']);
        $data['email_msg'] = $msg;

        $view = new View($params, $data, $calls);

        if (null === $view) {
            return null;
        }

        $result = $view->storedPassword();

        return $sec->exitApp([
            'userid' => $data['userid'],
            'msg' => $result['html'],
        ]);
    }

    public function invite($params, $data = null)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
        
        $calls = null;

        $mod = new Model();

        if($data === null){
            $data = $mod->invite($params);

            if (null === $data) {
                return null;
            }    
        }

        $calls = $mod->callsInviteStore($params);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, $data, $calls);

        return $view->invite();
    }

    public function inviteStore($params)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
        
        $data = null;

        $mod = new Model();

        $data = $mod->inviteStore($params);

        if ($data['return'] > 0) {
            return $this->invite($params, $data);
        }

        $calls = $mod->callsStore($params);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, $data, $calls);

        if (null === $view) {
            return null;
        }

        return $view->inviteCreated();
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }

}