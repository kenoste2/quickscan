<?php

class Application_Form_Code extends Zend_Form {

    public function init() {
        
        $this->setMethod('post');

        $functions = new Application_Model_CommonFunctions();


        $this->addElement(
            'text', 'code', array(
            'label' => '',
            'required' => true,
            'class' => 'bigform',
            'filters' => array('StringTrim'),
        ));

        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'class' => 'bigform',
            'label' => $functions->T('volgende'),
        ));
    }

}

