<?php
/**
 * PHPMailerSendGrid - PHPMailer extension to send emails via Sendgrid (or save to file).
 *
 * @see       https://github.com/rodrigoq/PHPMailerSendGrid/ The PHPMailerSendGrid GitHub project
 *
 * @license   https://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @note      This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace PHPMailer\PHPMailer;

use SendGrid\Mail\Mail;

/**
 * PHPMailerSendGrid - SendGrid email creation and transport class.
 *
 */
class PHPMailerSendGrid extends PHPMailer
{
    /**
     * The sendgrid api key.
     *
     * @var string
     */
    public $SendGridApiKey = '';

	/**
	 * The sendgrid host.
	 *
	 * @var string
	 */
	public $SendGridHost = '';

    /**
	 * The file path.
	 *
	 * @var string
	 */
    public $EmailFilePath = '';

    /**
     * Send messages using SendGrid.
     */
    public function isSendGrid()
    {
        $this->Mailer = 'sendgrid';
    }

    /**
     * Send messages using File.
     */
    public function isFile()
    {
        $this->Mailer = 'file';
    }

    /**
     * Send mail using SendGrid.
     *
     * @param string $header The message headers
     * @param string $body   The message body
     *
     * @throws Exception
     *
     * @return boolean
     */
    protected function sendgridSend($header, $body)
    {
        try {
            $mail = new Mail();

            $mail->setFrom($this->From, $this->FromName);
            $mail->setSubject($this->Subject);

            foreach ($this->to as $to) {
                $mail->addTo($to[0], $to[1]);
            }

            foreach ($this->cc as $cc) {
                $mail->addCc($cc[0], $cc[1]);
            }

            foreach ($this->bcc as $bcc) {
                $mail->addBcc($bcc[0], $bcc[1]);
            }

            if(count($this->ReplyTo) > 0) {
                $replyTo = array_shift($this->ReplyTo);
                $mail->setReplyTo($replyTo[0], $replyTo[1]);
            }

            if($this->alternativeExists()) {
                $mail->addContent('text/plain', $this->AltBody);
            }

            if(isset($this->Body)) {
                if($this->ContentType != 'text/plain') {
                    $this->ContentType = 'text/html';
                }
                $mail->addContent($this->ContentType, $this->Body);
            }

            foreach ($this->attachment as $attach) {
                $content = base64_encode(file_get_contents($attach[0]));
                //addAttachment(content, type, filename, disposition, contentId)
                $mail->addAttachment($content, $attach[4],
                    $attach[2], $attach[6], $attach[7]);
            }

			$options = array();
			if ($this->SendGridHost != '') {
				$options['host'] = $this->SendGridHost;
			}
			$sendgrid = new \SendGrid($this->SendGridApiKey, $options);
			$response = $sendgrid->send($mail);

            // Error codes: https://sendgrid.com/docs/API_Reference/Web_API_v3/Mail/errors.html
            if($response->statusCode() != 202) {
                $err = $response->statusCode() . ', ' . $response->body();
                $this->setError($err);
                $this->edebug($err);
                if ($this->exceptions) {
                    throw new Exception('Sendgrid error: ' . $err, self::STOP_CRITICAL);
                }
                return false;
            }
        } catch(\Exception $e) {
            $this->setError($e->getMessage());
            $this->edebug($e->getMessage());
            if ($this->exceptions) {
                throw new Exception('Sendgrid unexpected error: ' . $e->getMessage(), self::STOP_CRITICAL, $e);
            }
            return false;
        }
        return true;
    }

    /**
     * Send mail saving file, IE does not send mail.
     *
     * @param string $header The message headers
     * @param string $body   The message body
     *
     * @throws Exception
     *
     * @return bool
     */
    protected function fileSend($header, $body)
    {
        try {
            $file = $this->getFileName();
            file_put_contents($file, $header . $body);
        } catch(\Exception $e) {
            $this->setError($e->getMessage());
            $this->edebug($e->getMessage());
            if ($this->exceptions) {
                throw new Exception('FileSend error: ' . $e->getMessage(), self::STOP_CRITICAL, $e);
            }
            return false;
        }
        return true;
    }

    /**
     * Get filename for saving mail to disk.
     * Path from $EmailFilePath and name from date.
     *
     * @return string
     */
    private function getFileName()
    {
        $base = '';
        if($this->EmailFilePath != '')
            $base = rtrim($this->EmailFilePath, "/\\") . DIRECTORY_SEPARATOR;

        $base .= date('Ymd_His');
        $mili = 0;
        $file = $base.'.0000.txt';
        while(file_exists($file)) {
            $mili++;
            $file = $base . '.' . sprintf('%04d', $mili) . '.txt';
        }
        return $file;
    }
}
