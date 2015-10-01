<?php

class Zend_View_Helper_Amount extends Zend_View_Helper_Abstract {

    public function amount($amount) {
        $functions = new Application_Model_CommonFunctions();
        return $functions->amount($amount);
    }

}