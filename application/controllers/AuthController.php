<?php

class AuthController extends Zend_Controller_Action
{

    public function init()
    {
        global $config;

        $this->view->headerTitle = $config->appname;

        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function loginAction()
    {

        $loginForm = new Application_Form_Login();

        $companyForm = new Application_Form_GeneralCompany();

        if ($this->getParam('submit')) {
            $formData = $this->getRequest()->getParams();
            $answersObj = new Application_Model_Answers();
            $answersObj->save($formData['session'], $formData['question']);

            $data = array(
                'session' => $formData['session'],
            );

            $companyForm->populate($data);

        }
        $this->view->companyForm = $companyForm;


        if ($loginForm->isValid($_POST)) {

            $adapter = new Application_Model_AuthAdapterDbTable();

            $adapter->setIdentity($loginForm->getValue('username'));
            $adapter->setCredential($loginForm->getValue('password'));

            $result = $adapter->authenticate();

            if (!$adapter->isValid()) {
                $adapter->setCredential($loginForm->getValue('password'));
                $result = $adapter->authenticate();
            }

            if ($adapter->isValid()) {
                $this->redirect('/index/questions');
                return;
            } else {
                $this->view->showError = true;
            }
        }

        $this->view->loginForm = $loginForm;
    }

    public function registerAction()
    {
        $companyForm = new Application_Form_GeneralCompany();

        $uniqueId = uniqid();
        $this->view->session = $uniqueId;

        if ($this->getRequest()->isPost() && $this->getParam('submit')) {
            if ($companyForm->isValid($_POST)) {
                $data = $update = $companyForm->getValues();
                $formData = $this->getRequest()->getParams();
                $answersObj = new Application_Model_Answers();


                $usersObj = new Application_Model_Users();
                $userName = $usersObj->createUsername($formData['contact_name'], $formData['contact_forename']);
                $password = $usersObj->createRandomPassword();



                $usersData = array(
                    "name" => $formData['contact_name'],
                    "forename" => $formData['contact_forename'],
                    "email" => $formData['email'],
                    "tel" => $formData['tel'],
                    "username" => $userName,
                    "password" => $password,
                );

                $usersObj->create($usersData);


                $adapter = new Application_Model_AuthAdapterDbTable();

                $adapter->setIdentity($userName);
                $adapter->setCredential($password);
                $result = $adapter->authenticate();


                $data = array(
                    'session' => $formData['session'],
                    'name' => $formData['name'],
                    'vatnr' => $formData['vatnr'],
                    'nr_clients' => $formData['nr_clients'],
                    'type_clients' => $formData['type_clients'],
                );

                $answersObj->saveGeneral($data);

                $this->redirect("/index/index/session/{$formData['session']}");

            } else {
                $this->view->showError = true;
                $this->view->errors = $companyForm->getErrors();
            }
        } else {
            $data = array(
                'session' => $uniqueId,
            );

            $companyForm->populate($data);
        }
        $this->view->companyForm = $companyForm;

    }


    public function logoutAction()
    {
        $auth = new Application_Model_AuthAdapterDbTable();
        $auth->clearIdentity();
        $this->redirect('/auth/Login');
    }

}

