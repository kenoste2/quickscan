<?php

class Application_Form_SearchCompany extends Zend_Form {

    public function init() {

        global $db;
        $this->setMethod('post');
        $functions = new Application_Model_CommonFunctions();

        $this->addElement('hidden', 'session');
        $this->addElement('text', 'search', array('label'=> $functions->T('Zoek bedrijfsnaam'),'size' => 50, required => true));
        $this->addElement('password', 'password', array('label'=> $functions->T('toegangscode'),'size' => 15, required => true));
        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => $functions->T('verder'),
        ));
    }
}

