<?php

class BaseController extends Zend_Controller_Action {

    protected $db;
    protected $auth;
    protected $export;
    protected $functions;
    protected $saveDate;

    public function init() {
        global $db;
        global $config;
        global $lang;

        $this->db = $db;
        $this->functions = new Application_Model_CommonFunctions();
        $exportNamespace = new Zend_Session_Namespace('export');
        $this->export = $exportNamespace;

        $auth = new Application_Model_AuthAdapterDbTable();


        if ($this->getParam('selectlang')) {
            $lang = $this->getParam('selectlang');
            setcookie('lang', $lang , time() + 84600*30, '/');
        }

        $authNamespace = new Zend_Session_Namespace('Zend_Auth');

        $this->view->online_name = $authNamespace->online_name;

        $this->view->headerTitle = $config->appname;
    }

    public function hasAccess($resource) {
        $access = new Zend_Config_Ini(
                APPLICATION_PATH . '/configs/access.ini', APPLICATION_ENV);
        
        $accessArray = explode(",",$access->$resource);

        if (in_array($this->auth->online_rights, $accessArray)) {
            return true;
        }
        return false;
    }

    public function moduleAccess($resource) {
        $access = new Zend_Config_Ini(
            APPLICATION_PATH . '/configs/access.ini', APPLICATION_ENV);

        if ($access->modules->$resource == 'N') {
            return false;
        } else return true;
    }


    public function addData($tableName, $data, $returnField = false) {
        return $this->functions->saveData($tableName, $data, $where = false, $returnField);
    }
    public function saveData($tableName, $data, $where = false) {
        return $this->functions->saveData($tableName, $data, $where, $returnField);
    }

}

