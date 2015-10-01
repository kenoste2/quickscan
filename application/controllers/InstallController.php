<?php

require_once 'application/controllers/BaseController.php';

class InstallController extends BaseController
{

    public function indexAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        die("done");
    }


 
    public function updateAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();


        $content = file_get_contents("updates/recentupdates.sql");
        $queries = explode(";", $content);

        if (!empty($queries)) {
            foreach ($queries as $sql) {
                $this->db->query($sql);
            }
        }
        die("<br>System is up to date");
    }
}
