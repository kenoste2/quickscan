<?php


require_once 'application/models/Base.php';

class Application_Model_Mail extends Application_Model_Base {

    private $smtpServer = "";
    private $tr;
    private $bcc = "";
    private $cc = "";
    private $fromName = '';
    private $fromEmail = '';

    function __construct() {

        //$this->tr = new Zend_Mail_Transport_Smtp($this->smtpServer);
        $config = Zend_Registry::get('config');

        if (!empty($config->fromEmail)) {
            $this->fromEmail = $config->fromEmail;
        }
        if (!empty($config->fromName)) {
            $this->fromName = $config->fromName;
        }
        if (!empty($config->addBcc)) {
            $this->bcc = $config->addBcc;
        }
        if (!empty($config->addcc)) {
            $this->cc = $config->addcc;
        }
    }

    /**
     * Send a mail
     * @param String $to
     * @param String $subject
     * @param String $content
     * @param bool $contentText
     * @param array $attachments
     * @internal param \Binary $array $attachment
     * @return bool|\Email
     */
    public function sendMail($to, $subject, $content,$contentText = false, $attachments = false, $isUtf8 = false) {
        $mail = $isUtf8 ? new Zend_Mail('UTF-8') : new Zend_Mail();
        $mail->setFrom($this->fromEmail, $this->fromName);
        //$mail->setFrom("info@c-online.be", "Maarten");
        // @todo remove this testEmail after launch.
        //$to = "dequanter4web@gmail.com";

        if (stripos($to,";")) {
            $toArray = explode(";",$to);
            foreach ($toArray as $email) {
                if (!empty($email) && stripos($email,'@')) {
                    $mail->addTo($email);
                }
            }
        } else {
            $mail->addTo($to);
        }
        if (!empty($this->bcc)) {
            $mail->addBcc($this->bcc);
        }
        if (!empty($this->cc)) {
            $mail->addCc($this->cc);
        }
        $mail->setSubject($subject);
        $mail->setBodyText($contentText);
        $content = nl2br($content);
        $mail->setBodyHtml($content);
        //$mail->setDefaultTransport($this->tr);
        if (!empty($attachments)) {
            if (array_key_exists('content', $attachments)){
                $at = $mail->createAttachment($attachments['content']);
                $at->filename = $attachments['filename'];
            } else {
                foreach ($attachments as $attachment ) {
                    $at = $mail->createAttachment($attachment['content']);
                    $at->filename = $attachment['filename'];
                }
            }
        }

        if ($mail->send()) {
            return $to;
        } else {
            return false;
        }
    }
}

?>
