<?php

require_once 'application/controllers/BaseController.php';

class ReportController extends BaseController {

    public function indexAction() {
        if ($this->getParam('submit') && $this->getParam('password') == 'Turbo' ) {
            $data = $this->getRequest()->getParams();
            $search = $data['search'];

            $resultsObj = new Application_Model_Results();
            $results = $resultsObj->searchByName($search);

            $this->view->results = $results;
        }

        $form = new Application_Form_SearchCompany();
        $this->view->form = $form;
    }

    public function viewAction() {
        $this->view->hideBackground = true;

        $resultObj = new Application_Model_Results();
        $session = $this->getParam('session');
        $result = $resultObj->getResult($session);
        $this->view->result = $result;
        $this->view->answers = $resultObj->getAnswersPerCategory($session);
    }
}

