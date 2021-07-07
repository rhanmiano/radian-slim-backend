<?php

namespace App\Helpers;

/**
 * class EmailHelper
 *
 * Email utility class
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class EmailHelper {

    protected $mail;

    public function __construct(array $args) {
        $this->mail = new PHPMailer;

        //Tell PHPMailer to use SMTP
        $this->mail->isSMTP();

        //Enable SMTP debugging
        // SMTP::DEBUG_OFF = off (for production use)
        // SMTP::DEBUG_CLIENT = client messages
        // SMTP::DEBUG_SERVER = client and server messages
        $this->mail->SMTPDebug = SMTP::DEBUG_OFF;

        //Set the hostname of the mail server
        $this->mail->Host = 'smtp.gmail.com';
        // use
        // $this->mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6

        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $this->mail->Port = 587;

        //Set the encryption mechanism to use - STARTTLS or SMTPS
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        //Whether to use SMTP authentication
        $this->mail->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        $this->mail->Username = getenv("EMAIL_SENDER");

        //Password to use for SMTP authentication
        $this->mail->Password = getenv("EMAIL_PASS");

        //Set who the message is to be sent from
        $this->mail->setFrom($args['from_email'], $args['from_email_name']);

        //Set who the message is to be sent to
        $this->mail->addAddress($args['to_email'], $args['to_email_name']);

        //Set the subject line
        $this->mail->Subject = $args['subject'];

        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $this->mail->msgHTML($args['message']);
    }

    public function get() {
      return $this->mail;
    }

}