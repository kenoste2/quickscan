<?php

require_once 'application/models/Base.php';

class Application_Model_Results extends Application_Model_Base {

    public function getResult($session) {
        $answersObj = new Application_Model_Answers();
        $userObj = new Application_Model_Users();
        $username = $this->getUser($session);

        $result['general'] = $answersObj->getGeneralAnswersForSession($session);
        $result['categories'] = $this->getScoringForSession($session);
        $result['user'] = $userObj->getUser($username);
        return $result;
    }


    private function getUser($session) {
        $sql = "SELECT created_by FROM results WHERE session = '{$session}'";
        $userName = $this->db->get_var($sql);
        return $userName;
    }


    private function getScoringForSession($session) {
        
        $questionsObj = new Application_Model_Questions();
        $answersObj = new Application_Model_Answers();
        
        $categories = $questionsObj->getCategories();


        $data = array();


        $mainScore = 0;

        $categoryCounter = 0;

        foreach ($categories as $category) {
            $score = 0;
            $categoryCounter++;

            $questions = $questionsObj->getQuestionsForCategory($category->category);
            
            foreach($questions as $question) {
                $answer = $answersObj->getAnswerForQuestion($session, $question->id);

                if (stripos($answer,",") !== false ) {
                    $answers = explode(",",$answer);
                    foreach ($answers as $answer) {
                        $answerString = 'score'.$answer;
                        $score  += $question->$answerString;
                    }
                } else {
                    $answerString = 'score'.$answer;
                    $score  += $question->$answerString;
                }

            }
            $data[$category->category]['mainscore'] = $score;



            $score_total = $data[$category->category]['mainscore'] + $data[$category->category]['score_correction'];
            if ($score > 100) {
                $score = 100;
            }
            if ($score < 0) {
                $score = 0;
            }

            $data[$category->category]['score_total'] = $score;
            $mainScore += $score;
        }

        $mainScore = $mainScore / $categoryCounter;

        $data['mainscore'] = $mainScore;

        $this->saveResult($session, $data);
        return $data;
    }


    public function saveResult($session,$data) {


        unset($data['mainscore']);
        $accessSession = new Zend_Session_Namespace('Access');

        foreach ($data as $category => $result) {
            $exists = $this->db->get_var("SELECT COUNT(*) FROM results WHERE
            session = '{$session}'
            AND category = '{$category}'");

            if (!$exists) {
                $data = array (
                    'session' => $session,
                    'accesscode' =>  $accessSession->onlineAccesscode,
                    'category' => $category,
                    'mainscore' => $result['mainscore'],
                    'score_correction' => 0,
                    'score_total' => $result['score_total'],
                    'created' => date("Y-m-d"),
                    'created_by' => $this->online_user,
                );

                $this->saveData('results', $data);
            }
        }
    }


    public function searchByName($search)
    {
        $sql = "SELECT * FROM answers_general WHERE name LIKE '%{$search}%'";
        $results = $this->db->get_results($sql);
        return $results;
    }
    
    
    public function getAnswersPerCategory($session) {

        global $lang;

        $questionsObj = new Application_Model_Questions();
        $answersObj = new Application_Model_Answers();

        $categories = $questionsObj->getCategories();
        $data = array();
        foreach ($categories as $category) {
            
            $questions = $questionsObj->getQuestionsForCategory($category->category);
            foreach ($questions as $question) {
                
                $answer = "answer" . $answersObj->getAnswerForQuestion($session, $question->id);



                $data[$category->category][] = array(
                    'question' => $question->question,
                    'answer' => $question->$answer,
                );  
            }
            foreach ($questions as $question) {

                $answer = $answersObj->getAnswerForQuestion($session, $question->id);

                $answerArray = array();

                if (stripos($answer,",") !== false ) {
                    $answers = explode(",",$answer);

                    foreach ($answers as $answer) {
                        $answerString = 'answer'.$answer;
                        $answerArray[]  = $question->$answerString;
                    }
                } else {
                    $answerString = 'answer'.$answer;
                    $answerArray[] = $question->$answerString;
                }
                $data[$category->category][] = array(
                    'question' => $question->question,
                    'answer' => implode(' - ',$answerArray),
                );
            }
        }
        return $data;
        }

}

?>
