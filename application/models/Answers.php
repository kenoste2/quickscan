<?php

require_once 'application/models/Base.php';

class Application_Model_Answers extends Application_Model_Base
{

    public function save($session, $answers)
    {

        $accessSession = new Zend_Session_Namespace('Access');

        if ($answers) {
            foreach ($answers as $id => $value) {

                if (is_array($value)) {
                    $value = implode(",",$value);
                }

                $exists = $this->db->get_var("SELECT COUNT(*) FROM answers
                    WHERE session = '{$session}'
                    AND question_id = {$id}");
                if (!$exists) {
                    $data = array(
                        'session' => $session,
                        'accesscode' => $accessSession->onlineAccesscode,
                        'question_id' => $id,
                        'answer' => $value,
                        'created' => date("Y-m-d"),
                        'created_by' => $this->online_user,
                    );
                    $this->addData('answers', $data);
                }
            }
        }
        return true;
    }


    public function saveGeneral($data)
    {

        $exists = $this->checkAnswerExists($data['session']);
        $accessSession = new Zend_Session_Namespace('Access');

        if (!$exists) {
            $data['created'] = date("Y-m-d");
            $data['created_by'] = $this->online_user;
            $data['accesscode'] = $accessSession->onlineAccesscode;

            $this->addData('answers_general', $data);
        }
    }

    public function checkAnswerExists($session) {
        $exists = $this->db->get_var("SELECT COUNT(*) FROM answers_general
                    WHERE session = '{$session}'");
        return $exists;
    }


    public function getGeneralAnswersForSession($session) {
        $sql = "SELECT * from answers_general WHERE session = '{$session}' ";
        $row = $this->db->get_row($sql);
        return $row;
    }


    public function getAnswerForQuestion($session, $id) {
        $sql = "SELECT answer FROM answers
            WHERE session = '{$session}'
        AND question_id = {$id}";
        $value = $this->db->get_var($sql);
        return $value;
    }


}

?>
