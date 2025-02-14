<?php

namespace Synaptic4U\Core;

use Exception;

class App extends Exception
{
    //	Encryption Properties
    protected $keyid = 0;
    protected $clientPublicKey;
    protected $cipher;
    protected $encryption;
    protected $keyArray = [];

    //	App Params
    protected $params = [
        'sessionid' => null,
        'whoami' => null,
        'userid' => -100,
        'ip' => null,
        'controller' => null,
        'method' => null,
        'formArray' => [],
    ];

    //	Return Properties
    protected $loginMessage;
    protected $replySCRIPT;
    protected $replyHTML;

    //	Return array for JSON to AJAX
    protected $reply = ['html' => null, 'script' => null];
    protected $system_params;

    //	Security - User login & admin priviliges
    protected $sec;

    public function __construct()
    {
        try {
            $this->log([
                'Location' => __METHOD__.'() - FLOW DIAGRAM',
            ]);

            $start = microtime(true);

            $this->encryption = new Encryption();

            if (null === $this->encryption) {
                throw new Exception();
            }

            $this->params['sessionid'] = $this->encryption->hashString($start);

            $this->sec = new Security();

            if (null === $this->sec) {
                throw new Exception();
            }

            $this->getIPAddress();

            $this->userActivity();

            $this->getPost();

            $this->log([
                'Location' => __METHOD__.'() params',
                'start' => $start,
                'params' => $this->params,
            ]);

            $this->routeApp();
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                'params' => json_encode($this->params, JSON_PRETTY_PRINT),
                'Exception' => $e->__toString(),
            ]);

            $this->exitApp();
        } finally {
            /*
            * 	AJAX return is JSON object {html,system_params,script}
            * 	Encrypt and return "html & script & params"
            */

            //	NEW KEYS
            $this->buildKeys();

            // NEW REPLY
            $this->encryptReply();

            $finish = microtime(true);

            new Log([
                'Location' => __METHOD__.'() : TIMER',
                'sessionid' => $this->params['sessionid'],
                'controller' => $this->params['controller'],
                'method' => $this->params['method'],
                'start' => $start,
                'finish' => $finish,
                'time' => $finish - $start,
            ], 'timer');

            //	Return it all back to AJAX send() function.
            echo json_encode($this->reply);
        }
    }

    protected function encryptReply()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $this->reply['html'] = $this->encryption->sendEncrypted($this->clientPublicKey, $this->keyid, json_encode($this->replyHTML));

        $this->reply['system_params'] = $this->encryption->sendEncrypted($this->clientPublicKey, $this->keyid, json_encode($this->system_params));

        $this->reply['script'] = $this->encryption->sendEncrypted($this->clientPublicKey, $this->keyid, json_encode($this->replySCRIPT));

        //	Logs all App output
        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     'params' => json_encode($this->params, JSON_PRETTY_PRINT),
        //     'reply' => json_encode([$this->replyHTML, $this->replySCRIPT, $this->system_params], JSON_PRETTY_PRINT),
        //     'reply' => json_encode($this->reply, JSON_PRETTY_PRINT),
        // ]);
    }

    protected function buildKeys()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $this->keyArray = $this->encryption->sendPublicKeyPair();

        if (null === $this->keyArray) {
            $this->exitApp();
        }
        $userid = ($this->params['userid'] > 0) ? $this->params['userid'] : 3;

        $keyid = $this->encryption->scramble($this->keyArray['keyid'], 'keyid', $userid);

        $userid = $this->encryption->scramble($this->params['userid'], 'userid', $userid);

        if (null === $keyid) {
            $this->exitApp();
        }

        if (null === $userid) {
            $this->exitApp();
        }

        $serverPublicKey = $this->keyArray['serverPublicKey'];

        $this->system_params = '<p id="serverPublicKey">'.implode(',', $serverPublicKey).'</p>'.
                               '<p id="session">'.$keyid.'</p>'.
                               '<p id="val">'.$userid.'</p>';
    }

    /**
     * Gets the $_POST array.
     * Decrypts all variables and form array into the $params array.
     * On failure - logs user out.
     */
    protected function getPost()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $plaintext = null;

            $this->log([
                'Location' => __METHOD__.'() - RAW',
                'post' => json_encode($_POST, JSON_PRETTY_PRINT),
                '$sizeof($_POST)' => sizeof($_POST),
            ]);

            if (6 !== sizeof($_POST)) {
                throw new Exception('POST is empty');
            }

            if ($_FILES) {
                // $this->log([
                //     'Location' => __METHOD__.'() - FILES',
                //     'post' => json_encode($_POST, JSON_PRETTY_PRINT),
                // ]);
            }

            // $this->log([
            //     'Location' => __METHOD__.'() - RAW: 2',
            //     'post' => json_encode($_POST, JSON_PRETTY_PRINT),
            //     '$sizeof($_POST)' => sizeof($_POST),
            //     '$_POST[k]' => $_POST['k'],
            //     '$_POST[i]' => $_POST['i'],
            //     '$_POST[s]' => $_POST['s'],
            //     '$_POST[m]' => $_POST['m'],
            //     '$_POST[cpk]' => $_POST['cpk'],
            //     '$_POST[c]' => $_POST['c'],
            // ]);

            $this->keyid = $this->encryption->unscramble(filter_var($_POST['k'], FILTER_SANITIZE_STRING), 'keyid');
            $this->params['userid'] = $this->encryption->unscramble(filter_var($_POST['i'], FILTER_SANITIZE_STRING), 'userid');
            $this->params['method'] = $this->encryption->unscramble(filter_var($_POST['m'], FILTER_SANITIZE_STRING), 'method');
            $this->params['controller'] = $this->encryption->unscramble(filter_var($_POST['c'], FILTER_SANITIZE_STRING), 'controller');
            $this->clientPublicKey = filter_var($_POST['cpk'], FILTER_SANITIZE_STRING);
            $this->cipher = base64_decode($_POST['s']);

            switch (true) {
                case null === $this->keyid:
                    throw new Exception('Error with keyid');

                    break;

                case null === $this->params['userid']:
                    throw new Exception('Error with userid');

                    break;

                case null === $this->params['method']:
                    throw new Exception('Error with method');

                    break;

                case null === $this->params['controller']:
                    throw new Exception('Error with controller');

                    break;

                case null === $this->clientPublicKey:
                    throw new Exception('Error with clientPublicKey');

                    break;

                case null === $this->cipher:
                    throw new Exception('Error with cipher');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__.'() - Unencrypted Params: 3',
            //     'KeyId' => $this->keyid,
            //     'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            //     'ClinetPK' => $this->clientPublicKey,
            //     'Cipher' => $this->cipher,
            // ]);

            $plaintext = $this->encryption->recievePublicKeyPair($this->clientPublicKey, $this->keyid, $this->cipher);

            if (null === $plaintext) {
                throw new Exception('Error with decrypting cipher');
            }

            // $textFromCipher = json_decode($plaintext, false);
            // $objectFromCipher = json_decode($textFromCipher, false);

            $plaintext = json_decode($plaintext, true);
            $this->params['formArray'] = json_decode($plaintext, true);

            // $this->log([
            //     'Location' => __METHOD__.'() - Unencrypted Cipher: 4',
            //     'objectFromCipher is_object' => (is_object($objectFromCipher)) ? 'true' : 'false',
            //     'objectFromCipher is_array' => (is_array($objectFromCipher)) ? 'true' : 'false',
            //     'objectFromCipher is_string' => (is_string($objectFromCipher)) ? 'true' : 'false',
            //     'objectFromCipher decrypted' => serialize($objectFromCipher),
            //     'plaintext is_object' => (is_object($plaintext)) ? 'true' : 'false',
            //     'plaintext is_array' => (is_array($plaintext)) ? 'true' : 'false',
            //     'plaintext is_string' => (is_string($plaintext)) ? 'true' : 'false',
            //     'plaintext decrypted' => serialize($plaintext),
            //     '$this->params[formArray] is_array' => (is_array($this->params['formArray'])) ? 'true' : 'false',
            //     'formArray decrypted' => json_encode($this->params['formArray'], JSON_PRETTY_PRINT),
            // ]);

            // $this->log([
            //     'Location' => __METHOD__.'() - Unencrypted Cipher: 4',
            //     'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            // ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                'post' => json_encode($_POST, JSON_PRETTY_PRINT),
                '$sizeof($_POST)' => sizeof($_POST),
                'msg' => $e->__toString(),
            ]);

            $this->exitApp();
        }
    }

    /**
     * Creates new controller object and calls it's method.
     */
    protected function loadController()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $class = '\Synaptic4U\Packages\\'.$this->params['controller'];

            // $this->log([
            //     'Location' => __METHOD__.'(): 1',
            //     'params' => json_encode($this->params, JSON_PRETTY_PRINT),
            // ]);

            if (class_exists($class)) {
                $controller = new $class();

                if (method_exists($controller, $this->params['method'])) {
                    // $this->log([
                    //     'Location' => __METHOD__.'(): '.$this->params['method'].' exists',
                    //     'params' => json_encode($this->params, JSON_PRETTY_PRINT),
                    // ]);

                    $reply = call_user_func_array(
                        [
                            $controller,
                            $this->params['method'],
                        ],
                        [
                            &$this->params,
                        ]
                    );

                    if (null === $reply) {
                        throw new Exception('Controller: '.$this->params['controller'].' Method: '.$this->params['method'].'. : RETURNED NULL!!!');
                    }

                    $this->replyHTML .= $reply['html'];
                    $this->replySCRIPT .= $reply['script'];

                // $this->log([
                //         'Location' => __METHOD__.'(): 4',
                //         'reply' => json_encode($reply, JSON_PRETTY_PRINT),
                //         'params' => json_encode($this->params, JSON_PRETTY_PRINT),
                //     ]);
                } else {
                    throw new Exception("Can't find this method!");
                }
            } else {
                throw new Exception("Can't find this class!");
            }
        } catch (exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            $this->exitApp("Something didn\\'t work with what you gave me ;(");
        }
    }

    /**
     * Funnels the application call.
     * Switch for user login / confirm / create / resend / password update.
     * If login failed -> logs user out.
     */
    protected function routeApp()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        //	User Register, Resend, Login
        if (-100 === ((int) $this->params['userid'])) {
            switch (true) {
                case 'User\User' == ((string) $this->params['controller']) && 'create' == ((string) $this->params['method']):
                case 'User\User' == ((string) $this->params['controller']) && 'resend' == ((string) $this->params['method']):
                case 'User\User' == ((string) $this->params['controller']) && 'store' == ((string) $this->params['method']):
                case 'User\User' == ((string) $this->params['controller']) && ('forgot' === ((string) $this->params['method'])):
                case 'User\User' == ((string) $this->params['controller']) && ('updatePassword' === ((string) $this->params['method'])):
                case 'User\User' == ((string) $this->params['controller']) && ('storePassword' === ((string) $this->params['method'])):
                    $this->loadController();

                break;

                case 'User\User' == ((string) $this->params['controller']) && ('login' === ((string) $this->params['method'])):
                    $this->userLogin();

                break;

                case ('User\User' == ((string) $this->params['controller'])) && ('confirm' === ((string) $this->params['method'])):
                case ('User\User' == ((string) $this->params['controller'])) && ('invite' === ((string) $this->params['method'])):
                    $this->userConfirm();

                break;
            }
        }

        //	Failed login
        if (-1 === ((int) $this->params['userid'])) {
            // Redirects user back to index.php with the failed login message.
            $this->exitApp($this->loginMessage);
        }

        //	Succesful login : Checks whoAMI & Loads the Front Template
        if (((int) $this->params['userid']) > 0) {
            //	gets roles & privileges
            $this->params['whoami'] = $this->sec->getWhoAmI($this->params['userid']);

            //	True - Load App Container & Nav : False - Normal App call
            if ('confirm' === ((string) $this->params['method'])) {
                //	Page Template & Navigation & Logged in user
                //  Message & Main container
                $this->loadFrontTemplate();

                $this->loadController();
            } else {
                //	Normal App call
                $this->loadController();
            }
        }
    }

    /**
     * Loads the front HTML Template for the system.
     */
    protected function loadFrontTemplate()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        //	Navigation tabs
        $this->params['controller'] = 'FrontTemplate\FrontTemplate';

        $this->params['method'] = 'show';

        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     'params' => json_encode($this->params, JSON_PRETTY_PRINT),
        // ]);
    }

    /**
     * Logs the user out on error, logout, timeout.
     * Calls Config::getConfig().
     *
     * @param [type] $msg
     */
    protected function exitApp($msg = null)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $this->log([
            'Location' => __METHOD__.'()',
            'msg' => $msg,
            'params' => json_encode($this->params, JSON_PRETTY_PRINT),
        ]);

        $config = (new Config())->getConfig();

        $badreply = $this->sec->exitApp([
            'userid' => $this->params['userid'],
            'msg' => (null === $msg) ? 'A fly landed in the soup.<br>Email : '.$config['admin']['email'] : $msg,
        ]);

        $this->replyHTML = $badreply['html'];
        $this->replySCRIPT = $badreply['script'];
    }

    /**
     * Gets the user's IP Address and tests if it's a mobile device.
     * Calls App::isMobileDevice().
     */
    protected function getIPAddress()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $this->params['ip'] = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);

        $user = filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_STRING);

        $this->params['mobile'] = (int) $this->isMobileDevice();
    }

    /**
     * Checks if user exists and sends confirmation link to user, to complete login process.
     */
    protected function userLogin()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $confirmArray = $this->sec->login($this->params);

        $this->replyHTML = (isset($confirmArray['html'])) ? $confirmArray['html'] : '';

        $this->replySCRIPT = (isset($confirmArray['script'])) ? $confirmArray['script'] : '';

        // $this->log([
        //     'Location' => __METHOD__.'() - result',
        //     'reply' => json_encode($confirmArray, JSON_PRETTY_PRINT),
        // ]);
    }

    /**
     * Checks if the request comes from a mobile device.
     *
     * @return bool
     */
    protected function isMobileDevice()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        return preg_match('/(KFAPWI|android|avantgo|blackberry|bolt|boost|cricket|docomo |fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\\.browser|up\\.link|webos|wos)/i', $_SERVER['HTTP_USER_AGENT']);
    }

    /**
     *	Logs client in.
     *	On Start the userid = -100
     *	Returns on success $userid = userid & $message
     *	Returns on failure $userid = -1  & $message.
     */
    protected function userConfirm()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        $loginArray = $this->sec->confirm($this->params);

        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     'loginArray' => json_encode($loginArray, JSON_PRETTY_PRINT),
        // ]);

        if (isset($loginArray['error']) || null === $loginArray) {
            $badreply = $this->sec->exitApp([
                'userid' => (null === $loginArray) ? -100 : $loginArray['userid'],
                'msg' => (null === $loginArray) ? 'The link is invalid' : $loginArray['error'],
            ]);

            $this->replyHTML = $badreply['html'];
            $this->replySCRIPT = $badreply['script'];
        } else {
            $this->params['userid'] = $loginArray['userid'];

            $this->loginMessage = $loginArray['message'];

            // $this->log([
            //     'Location' => __METHOD__.'() - result',
            //     'loginArray' => json_encode($loginArray, JSON_PRETTY_PRINT),
            //     'params' => $this->params['userid'],
            // ]);
        }
    }

    /**
     * Checks the last user activity.
     * If no activity for 25min then logs the user out.
     * Class Security->userActivity('userid') : int.
     */
    protected function userActivity()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        if (-100 !== (int) $this->params['userid']) {
            $activity = null;

            $activity = $this->sec->userActivity($this->params['userid']);

            if (!is_null($activity) && (int) $activity > 25) {
                $result = $this->exitApp([
                    'userid' => $this->params['userid'],
                    'msg' => 'You have been logged out because you were inactive on the system for more then 25 minutes. Your last activity was '.$activity.' ago.',
                ]);

                $this->replyHTML = $result['html'];
                $this->replySCRIPT = $result['script'];
            }
        }
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
