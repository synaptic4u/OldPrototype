<?php

namespace Synaptic4U\Core;

use Synaptic4U\Packages\Authenticate\Authenticate;
use Synaptic4U\Packages\Communication\Communication;

/**
 * Class::Security
 * Security::__construct() :
 * Security::failedLogin() :
 * Security::login() :
 * Security::sendConfirmation() :
 * Security::sendInvite() :
 * Security::forgotPassword() :
 * Security::updatePassword() :
 * Security::confirm() :
 * Security::logout() :
 * Security::getWhoAmI() :
 * Security::userActivity() :
 * Security::exitApp() :
 * Security::clearUserLogin() :.
 *
 * Default system values in login_users table.
 * userid = -1 when email or passkey are not set.
 * userid = 0 when email & passkey are not found in users table.
 */
class Security
{
    protected $config;
    protected $auth;

    /**
     * Instantiates: Class::Authenticate & CLass::Config to local members.
     */
    public function __construct()
    {
        $this->auth = new Authenticate();
        $this->config = (new Config())->getConfig();
    }

    public function failedLogin($ip = '0.0.0.0', $userid = -1)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $this->auth->insertLogin([
            'ip' => $ip,
            'userid' => $userid,
            'loggedin' => 'now()',
            'loggedout' => 'now()',
        ]);

        $status = 'switch broke???';

        switch (true) {
            case -1 === $userid:
                $status = 'userid = -1 when email or passkey are not set.';

                break;

            case 0 === $userid:
                $status = 'userid = 0 when email & passkey are not found in users table.';

                break;

            case $userid > 0:
                $status = 'Failed login for another reason.';

                break;
        }
    }

    public function login($params = [])
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $message = '';
        $userid = -1;

        $this->clearUserLogin();

        if (strlen($params['formArray']['email']) > 7 && strlen($params['formArray']['passkey']) > 15) {
            // Check if client exists
            $auth_result = $this->auth->checkUserExists($params);

            if ((int) $auth_result['userid'] > 0 && 1 === (int) $auth_result['check']) {
                $loginid = $this->auth->insertLogin([
                    'ip' => $params['ip'],
                    'userid' => $auth_result['userid'],
                    'loggedin' => 'now()',
                    'loggedout' => 'now()',
                ]);

                if ((int) $loginid > 0) {
                    return $this->exitApp(['userid' => $auth_result['userid'], 'msg' => $this->sendConfirmation($params['formArray']['email'], $auth_result['userid'])]);
                }
            }

            // insert failed login
            $this->failedLogin($params['ip'], $userid);

            $message = 'Unsuccessful login!<br>Please make sure that your: Email and Password are correct.';

            return $this->exitApp([
                'userid' => $userid,
                'msg' => $message,
            ]);
        }

        // insert failed login
        $this->failedLogin($params['ip'], $userid);

        // If $_POST values not set.
        $message = 'Please try again';

        if (isset($params['formArray']['email']) && $params['formArray']['email'] > '') {
            $message .= '<br>Email is set.';
        } else {
            $message .= '<br>Email is blank.';
        }

        if (isset($params['formArray']['passkey']) && $params['formArray']['passkey'] > '') {
            $message .= '<br>Password is set.';
        } else {
            $message .= '<br>Password is blank.';
        }

        return $this->exitApp([
            'userid' => $userid,
            'msg' => $message,
        ]);
    }

    public function sendConfirmation($email, $userid)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $comm = new Communication();

        return $comm->sendConfirmation($this->config, [
            'email' => $email,
            'userid' => $userid,
        ]);
    }

    public function sendInvite($inviteid)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $comm = new Communication();

        return $comm->sendInvite($this->config, $inviteid);
    }

    public function forgotPassword($email)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        return $this->auth->forgotPassword($email);
    }

    public function updatePassword($seclink)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        return $this->auth->confirmSecLink($seclink[0]);
    }

    public function confirm($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $data = $this->auth->confirmSecLink($params['formArray'][0]);

        if (isset($data['error'])) {
            return $data;
        }

        if (isset($data['userid']) && (int) $data['userid'] > 0) {
            $this->auth->insertLogin([
                'ip' => $params['ip'],
                'userid' => $data['userid'],
                'loggedin' => 'now()',
                'loggedout' => null,
            ]);

            $data['message'] = ((int) $data > 0) ? 'Successfully logged in.' : 'Something went wrong with registering your log in.';

            if (null === $data) {
                $this->error([
                    'Location' => __METHOD__.'(): error',
                    'data' => $data,
                ]);
            }
        }

        return $data;
    }

    public function logout($userid = 0)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $data = $this->auth->logout($userid);
    }

    public function getWhoAmI($userid)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        return $this->auth->getWhoAmI($userid);
    }

    public function userActivity($userid)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        return $this->auth->userActivity($userid);
    }

    public function exitApp($params = ['userid' => 0, 'msg' => 'There was a fly in my soup.<br>;('])
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        if ((isset($params['userid'])) && ((int) $params['userid'] > 0)) {
            $this->logout($params['userid']);
        }

        $reply['html'] = '';

        $reply['script'] = "setTimeout(function(){
			window.location.href = 'logout.php?message=".$params['msg']."';},1);";

        return $reply;
    }

    public function clearUserLogin($limit = 1)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $this->auth->clearUserLogin($limit);
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }

    protected function error($msg)
    {
        new Log($msg, 'error', 3);
    }
}
