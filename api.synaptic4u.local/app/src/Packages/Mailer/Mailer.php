<?php

namespace Synaptic4U\Packages\Mailer;

use Exception;
use Mailer\SendMail;
use Synaptic4U\Core\Log;

class Mailer
{
    protected $mail;

    protected $output;

    protected $email;
    protected $url;

    protected $subject;
    protected $body;
    protected $alt_body;

    public function __construct()
    {
        $this->mail = new SendMail();

        // $this->log([
        //     'Location' => __METHOD__.'()',
        //     // 'mail' => serialize($this->mail),
        // ]);
    }

    public function sendSecLink($email, $url)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $this->subject = 'Synaptic4U Confirmation Link';

            $this->email = $email;

            $this->url = $url;

            $this->body = $this->bodySecLink();

            $this->alt_body = $this->altBodySecLink();

            $this->output = $this->mail->sendEmail([
                'to' => $this->email,
                'subject' => $this->subject,
                'body' => $this->body,
                'alt_body' => $this->alt_body,
            ]);

            // $this->message = $this->composeEmail();

            $this->log([
                'Location' => __METHOD__.'()',
                'to' => $this->email,
                'subject' => $this->subject,
                'body' => $this->body,
                'alt_body' => $this->alt_body,
                'output' => $this->output,
            ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                'Exception' => $e->__toString(),
                'output' => $this->output,
            ]);
        } finally {
            return $this->output;
        }
    }

    protected function bodySecLink()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        return '
            <html>

                <body>

                    <div rules="all" style="border:1px solid grey;border-radius:5px; width:450px;">
                        
                        <div>

                            <h3 style="width:350px;margin:auto;padding-top:1em;">Please click on the link below</h3>
                        </div>
                        
                        <div>
                        
                            <a href="'.$this->url.'" style="text-decoration:none;">
                            
                                <h4 style="width:250px;margin:auto;padding-top:1em;">Confirmation</h4>
                            </a>
                        </div>

                        <div>
                        
                            <h4 style="width:250px;margin:auto;padding-top:1em;padding-bottom:1em;">Thank you</h4>
                        </div>
                    </div>
                </body>
            </html>';
    }

    protected function altBodySecLink()
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        return 'Please click on the link below</h3>\r\n'.$this->url.'\r\nThank you';
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
