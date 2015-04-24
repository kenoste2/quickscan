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


            $mainQuestions = $questionsObj->getMainQuestionsForCategory($category->category);
            foreach($mainQuestions as $question) {
                $mainAnswer = 'score'.$answersObj->getAnswerForQuestion($session, $question->id);
                $score  += $question->$mainAnswer;
                
            }
            $data[$category->category]['mainscore'] = $score;

            $nonMainQuestions = $questionsObj->getNonMainQuestionsForCategory($category->category);
            $score = 0;
            foreach($nonMainQuestions as $question) {
                $answer = $mainAnswer . $answersObj->getAnswerForQuestion($session, $question->id);
                $score  += $question->$answer;
            }
            $data[$category->category]['score_correction'] = $score;


            $score_total = $data[$category->category]['mainscore'] + $data[$category->category]['score_correction'];
            if ($score_total > 100) {
                $score_total = 100;
            }
            if ($score_total < 0) {
                $score_total = 0;
            }

            $data[$category->category]['score_total'] = $score_total;
            $mainScore += $score_total;

        }

        $mainScore = $mainScore / $categoryCounter;

        $data['mainscore'] = $mainScore;


        $this->saveResult($session, $data);

        return $data;
    }


    public function saveResult($session,$data) {


        unset($data['mainscore']);

        foreach ($data as $category => $result) {
            $exists = $this->db->get_var("SELECT COUNT(*) FROM results WHERE
            session = '{$session}'
            AND category = '{$category}'");

            if (!$exists) {
                $data = array (
                    'session' => $session,
                    'category' => $category,
                    'mainscore' => $result['mainscore'],
                    'score_correction' => $result['score_correction'],
                    'score_total' => $result['score_total'],
                    'created' => date("Y-m-d"),
                    'created_by' => $this->online_user,
                );

                $this->saveData('results', $data);
            }
        }
    }

}

?>
