<?php

require_once 'application/controllers/BaseController.php';

class messageController extends BaseController {

    public function indexAction() {
        $form =  new Application_Form_Register();

        $obj = new Application_Model_Messages();


        $data = array();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $data = $update = $form->getValues();
                $obj->add($update);
                $this->view->formSaved = true;
            } else {
                $this->view->formError = true;
                $this->view->errors = $form->getErrors();
            }
        } else {
            $data = array();
            $data['doel'] = 'S';
        }
        $form->populate($data);
        $this->view->registerForm = $form;
    }


    public function contactAction(){


        $messagesObj = new Application_Model_Messages();
        $form = new Application_Form_ContactMessage();

        $id = $this->getParam('id');
        $message = $messagesObj->get($id);
        $this->view->message = $message;


        $data = array();
        $data['messages_id'] = $id;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $data = $update = $form->getValues();
                $this->view->formSaved = true;
                $contact = new Application_Model_MessagesReactions();
                $contact->add($data);
            } else {
                $this->view->formError = true;
                $this->view->errors = $form->getErrors();
            }
        }
        $form->populate($data);



        $this->view->form = $form;





    }


}

