<?php 
/**
 * @package    PHPRestApi
 * @author     Roystan Smith <roystansmith@gmail.com>
 * @copyright  2021 RoystanSmith
 * @version    1.0.0
 * @since      File available since Release 1.0.0
 */

 // Namespaces
define('API_NAMESPACE',          'PHPRestApi');
define('API_DIR_ROOT',            dirname(__FILE__));
define('API_DIR_CONTROLLERS',     API_DIR_ROOT . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'v1' . DIRECTORY_SEPARATOR);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once API_DIR_ROOT . DIRECTORY_SEPARATOR . 'autoload.php'; 

require_once('api/api.php');

// Empty arrays
$inputs = array();
$post = array();

// URI Data
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']) ); 
$inputs['URI'] = '/'.substr_replace($_SERVER['REQUEST_URI'], '', 0, strlen($scriptName));
$inputs['URI'] = str_replace('//', '/', $inputs['URI']);

// Method
// POST - PUT - GET - DELETE
$inputs['method'] = @$_SERVER['REQUEST_METHOD'];

// Raw input for requests
$inputs['raw_input'] = @file_get_contents('php://input');

// POST data
@parse_str($inputs['raw_input'] , $post);

// Merge all
$inputs = array_merge($inputs,$post);

// Api
$app = new API($inputs);
$app->run();

