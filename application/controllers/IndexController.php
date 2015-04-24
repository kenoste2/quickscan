<?php

require_once 'application/controllers/BaseController.php';

class IndexController extends BaseController {

    public function indexAction() {
    }

    public function questionsAction() {
        $questionsObj =  new Application_Model_Questions();


        if (!$this->getParam('session')) {
            $uniqueId = uniqid();
            $this->view->session = $uniqueId;
        } else {
            $this->view->session = $this->getParam('session');
        }


        $this->view->questions = $questionsObj->getQuestionsGroupedByTheme();
        $this->view->themes = $questionsObj->getThemes();


        if ($this->getParam('submit')) {
            $formData = $this->getRequest()->getParams();
            $answersArray = $formData['question'];
            $this->view->answers = $answersArray;

            $incompleteQuestions = array();
            if (count($this->view->questions)) {
                for ($i=1;$i<=$questionsObj->getNumberQuestions(); $i++) {
                    if (!array_key_exists($i,$answersArray)) {
                        $incompleteQuestions[] = $i;
                    }
                }
            }

            if (empty($incompleteQuestions)) {
                $answersObj = new Application_Model_Answers();
                $answersObj->save($formData['session'],$formData['question']);
                $this->redirect("/index/verify/session/{$formData['session']}");
            } else {
                $this->view->incompleteQuestions = $incompleteQuestions;
                $this->view->showError = 1;
            }
        }

    }
    public function verifyAction() {

        $answersObj = new Application_Model_Answers();
        $companyForm = new Application_Form_GeneralCompany();




        if ($this->getParam('submit')) {
            $formData = $this->getRequest()->getParams();
            $answersObj->save($formData['session'],$formData['question']);
        }

        $this->view->companyForm = $companyForm;

        if ($this->getRequest()->isPost() && $this->getParam('companyform')) {
            if ($companyForm->isValid($_POST)) {
                $data = $update = $companyForm->getValues();
                $formData = $this->getRequest()->getParams();
                $answersObj = new Application_Model_Answers();

                $data = array(
                    'session' => $formData['session'],
                    'name' => $formData['name'],
                    'contact_name' => $formData['contact_name'],
                    'contact_forename' => $formData['contact_forename'],
                    'email' => $formData['email'],
                    'tel' => $formData['tel'],
                    'vatnr' => $formData['vatnr'],
                    'nr_clients' => $formData['nr_clients'],
                    'sector' => $formData['sector'],
                    'type_clients' => $formData['type_clients'],
                );

                $answersObj->saveGeneral($data);

                $resultObj = new Application_Model_Results();
                $result = $resultObj->getResult($formData['session']);


                $content = "Beste,

                Er werd een nieuwe quickscan uitgevoer voor bedrijf {$result['general']->name}.

                Gegevens :

                Naam : {$result['general']->name}
                Ond. nummer : {$result['general']->vatnr}
                Contactpersoon : {$result['general']->contact_forename}  {$result['general']->contact_name}
                Email :  {$result['general']->email}
                Tel :  {$result['general']->tel}

                Deze kan worden geraadpleegd via de url :

                http://quickscan.aaa.be/index/report/session/{$formData['session']}.

                Met vriendelijke groet,
                Quickscan";
                $mail = new Application_Model_Mail();
                $mail->sendMail("software@aaa.be", "Nieuwe quickscan voor {$result['general']->name}", $content,$contentText = false, $attachments = false, $isUtf8 = false);
                $this->redirect("/index/report/session/{$formData['session']}");
            } else {
                $this->view->showError = true;
                $this->view->errors = $companyForm->getErrors();
                }
        }
    }

    public function reportAction() {

        $resultObj = new Application_Model_Results();
        $session = $this->getParam('session');
        $result = $resultObj->getResult($session);
        $this->view->result = $result;
    }
}

