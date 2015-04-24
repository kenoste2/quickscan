<?php

class Application_Form_GeneralCompany extends Zend_Form {

    public function init() {

        global $db;
        $this->setMethod('post');
        $functions = new Application_Model_CommonFunctions();

        $this->addElement('hidden', 'session');
        $this->addElement('text', 'contact_name', array('label'=> $functions->T('uw_naam'),'size' => 50, required => true));
        $this->addElement('text', 'contact_forename', array('label'=> $functions->T('uw_voornaam'),'size' => 50, required => true));
        $this->addElement('text', 'name', array('label'=> $functions->T('bedrijfsnaam'),'size' => 50, required => true));
        $this->addElement('text', 'email', array('label'=> $functions->T('email'),'size' => 50, required => true, 'validators'=>array (array('EmailAddress')),));
        $this->addElement('text', 'tel', array('label'=> $functions->T('tel_mobiel'),'size' => 50, required => true));
        $this->addElement('text', 'vatnr', array('label'=> $functions->T('ondernemingsnummer'),'size' => 50, required => true));
        $this->addElement('select', 'nr_clients', array('label'=> $functions->T('actieve_klanten'),'MultiOptions' => $functions->texttolist('actieve_klanten_lijst'), required => true));
        $this->addElement('radio', 'type_clients', array('label'=> $functions->T('samenstelling_klanten') ,'MultiOptions' => $functions->texttolist('typeklanten_lijst'), required => true));
        $this->addElement('select', 'sector', array('label'=> $functions->T('sector') ,'MultiOptions' => $functions->texttolist('sector_lijst'), required => true));

        $this->addElement('Captcha','robot', array(
            'label' => $functions->T('beveiligingscode'),
            'captcha' => array(
                'captcha' => 'Figlet',
                'wordLen' => 6,
                'timeout' => 300,
            ),
        ));

        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => $functions->T('uw_gegevens_opslaan'),
        ));
    }
}

