<?php

namespace Api\Services;

use PHPMailer\PHPMailer\PHPMailer;

class EmailHelper {

    const DEBUG = true;

    public $log = Array();

    public function sendEmail($fromEmail = 'support@bitminepool.com', $fromEmailName = 'Support', $toEmail, $toEmailName, $subject = '', $body = '') {
        try {
            /* $mail = new PHPMailer;
              //To address and name
              $mail->addAddress($toEmail, $toEmailName);
              $mail->setFrom($fromEmail, $fromEmailName);
              //Send HTML or Plain Text email
              $mail->isHTML(true);
              $mail->isSMTP();

              $mail->Subject = $subject;
              $mail->Body = $body;
              $mail->AltBody = "This is the plain text version of the email content";

              if (!$mail->send()) {
              return $mail->ErrorInfo;
              } else {
              return 1;
              } */
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
            $headers .= "From: ".$fromEmail."" . "\r\n" .
                    "Reply-To: ".$fromEmail."" . "\r\n" .
                    "X-Mailer: PHP/" . phpversion();

// More headers
            $headers .= 'From: <'.$fromEmail.'>' . "\r\n";
            $headers .= 'Cc: '.$fromEmail.'' . "\r\n";

            mail($toEmail, $subject, $body, $headers);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}
