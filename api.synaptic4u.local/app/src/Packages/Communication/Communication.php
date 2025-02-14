<?php

namespace Synaptic4U\Packages\Communication;

use Mailer\SendMail;
use Synaptic4U\Core\Log;

class Communication
{
    public function sendInvite($params = null, $inviteid)
    {
        $data = null;
        $calls = null;

        $this->log([
            'Location' => __METHOD__.'(): 1',
            'params' => json_encode($params, JSON_PRETTY_PRINT),
            'inviteid' => $inviteid,
        ]);

        $mod = new Model($inviteid);

        $data = $mod->sendInvite($inviteid);

        if (null === $data) {
            return null;
        }

        $calls = $mod->callsSendInvite($data['from_userid'], $data['to_userid']);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, $data, $calls);

        $email = $view->sendInvite();

        $this->log([
            'Location' => __METHOD__.'(): 1',
            'params' => json_encode($params, JSON_PRETTY_PRINT),
            'calls' => json_encode($calls, JSON_PRETTY_PRINT),
            'data' => json_encode($data, JSON_PRETTY_PRINT),
            'email' => json_encode($email, JSON_PRETTY_PRINT),
            'inviteid' => $inviteid,
        ]);

        $mail = new SendMail();

        $output = $mail->sendEmail([
            'to' => $data['to_email'],
            'subject' => 'Synaptic4U Invitation Confirmation Link',
            'body' => $email['email'],
            'alt_body' => 'Please click on the link below</h3>\r\n'.$email['url'].'\r\nThank you',
        ]);

        if (1 === (int) $output) {
            $msg = 'A confirmation email has been sent to '.$data['to_email'].'.';
        } else {
            $msg = 'We were unable to send a invite to '.$data['to_email'].'.<br>Please contact me at: '.$params['admin']['email'].'.';
        }

        return $msg;
    }

    public function sendConfirmation($config = null, $data = null)
    {
        $calls = null;

        if (is_null($data) || is_null($config)) {
            return null;
        }

        $params = $config;

        $mod = new Model();

        $calls = $mod->callsConfirmation($data['userid']);

        if (null === $calls) {
            return null;
        }

        $view = new View($params, $data, $calls);

        $mail = new SendMail();

        $email_result = $view->sendConfirmation();

        $this->log([
            'Location' => __METHOD__.'(): 1',
            'email' => $email_result['email'],
            'url' => $email_result['url'],
            'calls' => $calls,
            'data' => $data,
            'params' => $params,
        ]);

        $output = $mail->sendEmail([
            'to' => $data['email'],
            'subject' => 'Synaptic4U Login Confirmation Link',
            'body' => $email_result['email'],
            'alt_body' => 'Please click on the link below</h3>\r\n'.$email_result['url'].'\r\nThank you',
        ]);

        if (1 === (int) $output) {
            $msg = 'A confirmation email has been sent to '.$data['email'].'.';
        } else {
            $msg = 'We were unable to send a invite to '.$data['email'].'.<br>Please contact me at '.$params['admin']['email'].'.';
        }

        return $msg;
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }
}
