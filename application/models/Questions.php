<?php

require_once 'application/models/Base.php';

class Application_Model_Questions extends Application_Model_Base {

    public function getQuestionsGroupedByCategory() {

        global $lang;

        $sql = "SELECT
            question_{$lang} as question,
            answer1_{$lang} as answer1,
            answer2_{$lang} as answer2,
            answer3_{$lang} as answer3,
            answer4_{$lang} as answer4,
            questiontype , score1, score2, score3, score4, id ,
            category_{$lang} as category
        FROM questions ORDER BY category_{$lang}, id";

        $results = $this->db->get_results($sql);

        $list = array();

        if ($results) {
            foreach ($results as $row) {
                $list[$row->category][$row->id] = array
                (
                    'question' => $row->question,
                    'answer1' => $row->answer1,
                    'answer2' => $row->answer2,
                    'answer3' => $row->answer3,
                    'answer4' => $row->answer4,
                    'score1' => $row->score1,
                    'score2' => $row->score2,
                    'score3' => $row->score3,
                    'score4' => $row->score4,
                    'questiontype' => $row->questiontype,
                );
            }
        }
        return $list;
    }

    public function getQuestionsGroupedByTheme() {

        global $lang;

        $sql = "SELECT
            question_{$lang} as question,
            answer1_{$lang} as answer1,
            answer2_{$lang} as answer2,
            answer3_{$lang} as answer3,
            answer4_{$lang} as answer4,
            mainquestion, score1, score2, score3, score4, id,
            theme_{$lang} as theme
        FROM questions ORDER BY theme_{$lang}, id";

        $results = $this->db->get_results($sql);

        $list = array();

        if ($results) {
            foreach ($results as $row) {
                $list[$row->theme][$row->id] = array
                (
                    'question' => $row->question,
                    'answer1' => $row->answer1,
                    'answer2' => $row->answer2,
                    'answer3' => $row->answer3,
                    'answer4' => $row->answer4,
                );
            }
        }
        
        return $list;
    }
    public function getCategories() {
        global $lang;

        $results = $this->db->get_results("SELECT category_{$lang} as category
            FROM questions
                GROUP BY category_{$lang}
                ORDER BY category_{$lang}");
        return $results;
    }

    public function getThemes() {
        global $lang;

        $results = $this->db->get_results("SELECT theme_{$lang} as theme
            FROM questions
                GROUP BY theme_{$lang}
                ORDER BY id");
        return $results;
    }

    public function getQuestionsForCategory($category)
    {
        global $lang;

        $results = $this->db->get_results("SELECT question_{$lang} as question,
            answer1_{$lang} as answer1,
            answer2_{$lang} as answer2,
            answer3_{$lang} as answer3,
            answer4_{$lang} as answer4
            ,score1, score2, score3, score4, id,
            category_{$lang} as category FROM questions
            WHERE category_{$lang} = '{$category}'
            ORDER BY id");

        return $results;
    }
    public function getNonMainQuestionsForCategory($category)
    {
        global $lang;

        $sql = "SELECT question_{$lang} as question,
            answer1_{$lang} as answer1,
            answer2_{$lang} as answer2,
            answer3_{$lang} as answer3,
            answer4_{$lang} as answer4,
            mainquestion,score1,score2,score3,score4,score11,score12,score13,score14,score21,score22,score23,score24,score31,score32,score33,score34,score41,score42, score43,score44,id,
            theme_{$lang} as theme FROM questions
            WHERE category_{$lang} = '{$category}'
            AND mainquestion = 0 ORDER BY id";
        $results = $this->db->get_results($sql);
        return $results;
    }

    public function getNumberQuestions() {
        $sql = "SELECT COUNT(*) FROM questions";
        $count = $this->db->get_var($sql);
        return $count;
    }







}

?>
