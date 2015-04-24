<?php


ini_set("auto_detect_line_endings", "1");


if (!empty($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'nl';
}




// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);



$config = new Zend_Config_Ini(
    APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
$baseUrl = $config->baseHttp;
define('BASE_URL', $baseUrl);

Zend_Registry::set('config', $config);


/** database */


include_once "ez_sql_core.php";
// Include ezSQL database specific component
include_once "ez_sql_mysql.php";
// Initialise database object and establish a connection
// at the same time - db_user / db_password / db_name / db_host


$db = new ezSQL_mysql($config->db->user,$config->db->password,$config->db->name,'localhost',$config->db->charset);

$application->bootstrap()
    ->run();