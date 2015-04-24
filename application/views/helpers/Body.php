<?php

class Zend_View_Helper_Body extends Zend_View_Helper_Abstract {

    public function body($text) {
        return nl2br($text);
    }

}