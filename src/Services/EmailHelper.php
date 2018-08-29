<?php

namespace Api\Services;
use PHPMailer\PHPMailer\PHPMailer;
class EmailHelper {

    const DEBUG = true;
    public $log = Array();
    
    public function sendEmail($fromEmail='support@bitminepool.com',$fromEmailName='Support',$toEmail,$toEmailName,$subject='',$body=''){
        
        $mail = new PHPMailer;
        //To address and name
        $mail->addAddress($toEmail, $toEmailName);

        //Send HTML or Plain Text email
        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = "This is the plain text version of the email content";

        if(!$mail->send()) 
        {
           return 0; 
        } else {
            return 1; 
        }
    }
}
