<?php

namespace Mailer;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Synaptic4U\Core\Log;

class SendMail
{
    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     // 'mail' => serialize($this->mail),
        // ]);
    }

    public function sendEmail(array $email)
    {
        // $this->log([
        //     'Location' => __METHOD__.'() - FLOW DIAGRAM',
        // ]);
     
        $success = null;

        try {
            $start = microtime(true);

            //Server settings
            $this->mail->SMTPDebug = SMTP::DEBUG_OFF;
            $this->mail->isSMTP();
            $this->mail->Host = 'mail.synaptic4u.co.za';
            $this->mail->SMTPSecure = 'ssl';

            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'bongani@synaptic4u.co.za';
            $this->mail->Password = 'xxxxxxxx';
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $this->mail->Port = 465;

            //Recipients
            $this->mail->setFrom('bongani@synaptic4u.co.za', 'Bongani @ Synaptic4U - No-Reply');
            // $this->mail->addAddress('system@synaptic4u.co.za', 'Bongani @ Synaptic4U System');
            $this->mail->addAddress($email['to']);
            $this->mail->addReplyTo('system@synaptic4u.co.za', 'Bongani @ Synaptic4U System');
            // $this->mail->addCC('cc@example.com');
            $this->mail->addBCC('bongani@synaptic4u.co.za');

            //Attachments
            // $this->mail->addAttachment('/var/tmp/file.tar.gz');
            // $this->mail->addAttachment('/tmp/image.jpg', 'new.jpg');

            //Content
            $this->mail->isHTML(true);
            $this->mail->Subject = $email['subject'];
            $this->mail->Body = $email['body'];
            $this->mail->AltBody = $email['alt_body'];

            $success = $this->mail->send();

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'email' => $email
            // ]);

            return $success;
        } catch (Exception $e) {
            $success = null;

            new Log([
                'Location' => __METHOD__.'()',
                // 'mail' => serialize($this->mail),
                'error' => "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}",
            ], 'error');
        } finally {
            $finish = microtime(true);

            new Log([
                'Location' => __METHOD__.'() : TIMER',
                'start' => $start,
                'finish' => $finish,
                'time' => $finish - $start,
            ], 'timer');

            return $success;
        }
    }

    protected function log($msg)
    {
        new Log($msg);
    }
}