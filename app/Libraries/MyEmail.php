<?php

namespace App\Libraries;

require_once APPPATH . 'Libraries/PHPMailer/PHPMailer.php';
require_once APPPATH . 'Libraries/PHPMailer/SMTP.php';
require_once APPPATH . 'Libraries/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MyEmail
{
    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        // Setup SMTP
        $this->mail->isSMTP();
        $this->mail->Host       = 'smtp.sendgrid.net'; // Your SMTP server
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = env('EMAIL_USR'); // SMTP username
        $this->mail->Password   = env('EMAIL_KEY');    // SMTP password
        $this->mail->SMTPSecure = 'tls'; // Or 'ssl'
        $this->mail->Port       = 587;   // Or 465 for SSL

        // Default sender
        $this->mail->setFrom('mdarc-memberships@arrleb.org', 'MDARC Membership Chair');
        $this->mail->addReplyTo('mdarc-memberships@arrleb.org', 'MDARC Membership Chair');
    }

    public function sendMail($to, $subject, $body)
    {
        try {
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;
            $this->mail->isHTML(true);

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            log_message('error', 'Mail Error: ' . $this->mail->ErrorInfo);
            return false;
        }
    }
}
