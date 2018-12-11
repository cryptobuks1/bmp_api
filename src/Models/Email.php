<?php

namespace Api\Models;

use Api\Models\ApiModel;
use Exception;
use PDO;


class Email extends ApiModel {

    /**
     * table name used by model 
     * @var string
     */
    public $tableName = 'system_emails'; //Initialize table name for model

    /**
     * Accept parameters to get sms templetes
     * @param type $params
     * @return Boolean
     */

    public function getEmailTemplate($emailTemplateName) {
        try {
//            $stmt = $this->pdo->prepare("CALL getEmailTemplate(?)");
            $stmt = $this->pdo->prepare("SELECT email_to, email_cc, email_bcc, email_from, subject, text1, text2 , tags FROM system_emails WHERE name = ?");
            $stmt->execute(array($emailTemplateName));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return $result;
            } else {
                return "";
            }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * The columns of table(STATIC)
     * @return array
     */
    public function getAllTableFields() {
        return[
            'id', 'name', 'description', 'email_to', 'email_cc', 'email_bcc', 'email_from', 'subject', 'text1', 'text2', 'email_type', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'
        ];
    }

    /**
     * Send Email to user mobile
     * return success response or error response in json 
     */
    public function getEmailContent($emailTemplateName, $vars, $params = []) {

        try {

            $emailContent = $this->getEmailTemplate($emailTemplateName);
            $replacements = [];
            $patterns = [];
            
            if (!empty($emailContent)) {
                $emailMessage = $emailContent['text1'];

                foreach ($vars as $key => $var) {
                    $emailMessage = preg_replace('/{\$(' . preg_quote($key) . ')}/i', $var, $emailMessage);
                }

                $emailSignature = $emailContent['text2'];
                foreach ($vars as $key => $var) {
                    $emailSignature = preg_replace('/{\$(' . preg_quote($key) . ')}/i', $var, $emailSignature);
                }

                $emailSubject = $emailContent['subject'];
                foreach ($vars as $key => $var) {
                    $emailSubject = preg_replace('/{\$(' . preg_quote($key) . ')}/i', $var, $emailSubject);
                }

                if (strpos($emailContent['email_from'], '<') !== false) {
                    $str = explode('<', $emailContent['email_from']);
                    $email = preg_replace('/\>/', '', $str[1]);
                    $name = $str[0];
                    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
                }
                if (isset($vars['fromName']) && !empty($vars['fromName'])) {
                    $name = $vars['fromName'];
                }
                if (isset($vars['fromEmail']) && !empty($vars['fromEmail'])) {
                    $email = $vars['fromEmail'];
                }

                $subject = preg_replace($patterns, $replacements, $emailSubject);

                $emailMessage = html_entity_decode($emailMessage);

                $tags = [];
                if (!empty($emailContent['tags'])) {
                    $tags = explode(',', $emailContent['tags']);
                }

                $ccAddress = [];
                if ($emailContent['email_cc']) {
                    $allCc = explode(',', $emailContent['email_cc']);
                    foreach ($allCc as $cc) {
                        $ccAddress[] = [
                            "name" => "",
                            "email" => $cc,
                        ];
                    }
                }
                return $emailMessage;
                /*
                $emailData = [
                    'isHtml' => true,
                    'subject' => $subject,
                    'message' => $emailMessage,
                    'to' => [
                        [
                            "name" => "",
                            "email" => $to,
                        ]],
                    'cc' =>
                    $ccAddress
                    ,
                    'from' => [
                        "name" => $name,
                        "email" => $email,
                    ],
                    'tags' => $tags,
                    'delay' => isset($params['delay']) ? $params['delay'] : 5,
                    'deliveryTime' => isset($params['deliveryTime']) ? $params['deliveryTime'] : '',
                    'campaignId' => isset($params['campaignId']) ? $params['campaignId'] : '',
                        //'attachment' => isset($vars['attachment']) ? $vars['attachment'] : '',
                ];

                $status = $mailgunHelper->send($emailData);
                //$status = 1;
                if ($status) {
                    //Message sent
                }
                return $status;*/
            } else {
                return 0;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

}
