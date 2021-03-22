<?php

require_once('router.php');
require_once('controllers/v1/EmployeeController.php');

class API
{
    // Input data
    private $_URI;          // URI
    private $_method;       // GET - POST - PUT - DELETE
    private $_rawInput;     // Raw input

    function __construct($inputs)
    {
        // HTTP inputs
        $this->_URI =       $this->_checkKey('URI', $inputs);
        $this->_rawInput =  $this->_checkKey('raw_input', $inputs);
        $this->_method =    $this->_checkKey('method', $inputs);
    }

    //Return NULL if the key does not exist
    private function _checkKey($key, $array){
        return array_key_exists($key, $array) ? $array[$key] : NULL;
    }

    public function run() {

        // Create the router
        $router = new Router();

        // Populate the router

        // GET homepage
        $router->addRoute('GET', '/', function() {
            echo "Home page";
        });

        $router->addRoute('GET', '/api/v1/employees' , 'EmployeeController#all', function() {
            (new EmployeeController())->all();
        });

        $router->addRoute('GET', '/api/v1/employee/:id' , 'EmployeeController#find', function($id) {
            (new EmployeeController())->find($id);
        });

        $router->addRoute('POST', '/api/v1/add-employee' , 'EmployeeController#insert', function() {
            (new EmployeeController())->insert();
        });

        $router->addRoute('PUT', '/api/v1/update-employee' , 'EmployeeController#update', function() {
            (new EmployeeController())->update();
        });

        $router->addRoute('DELETE', '/api/v1/delete-employee' , 'EmployeeController#delete', function() {
            (new EmployeeController())->delete();
        });

        // Run the router
        $router->run($this->_method, $this->_URI);
    }
}