<?php

include_once 'config/DB.php';
include_once 'models/employee.php';

class EmployeeController {

    public function all() {
        $employee = new Employee();
        $stmt = $employee->findAll();

        $itemCount = $stmt->rowCount();
        $employeeArr = array();

        if ($itemCount > 0) {

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $e = array(
                    "id" => $id,
                    "name" => $name,
                    "email" => $email,
                    "age" => $age,
                    "designation" => $designation,
                    "created" => $created
                );

                array_push($employeeArr, $e);
            }
            $code = 200;
            $message  = 'Success!';

        } else {
            $code = 404;
            $message  = 'No record found.';
        }

        $this->send_response($message, $employeeArr, $code);
    }

    public function find( $id ) {
        $employee = new Employee();
        $employeeRow = $employee->find( $id );
        $emp_arr = [];

        if ($employeeRow['name'] != null) {
            $emp_arr = array(
                "id" =>  $employeeRow['id'],
                "name" => $employeeRow['name'],
                "email" => $employeeRow['email'],
                "age" => $employeeRow['age'],
                "designation" => $employeeRow['designation'],
                "created" => $employeeRow['created']
            );
        
            $code = 200;
            $message  = 'Success!';

        } else {
            $code = 404;
            $message  = 'Employee not found.';
        }

        $this->send_response($message, $emp_arr, $code);
    }

    public function insert() {
        $employee = new Employee();

        $data = json_decode(file_get_contents("php://input"));
        $data->created = date('Y-m-d');

        if( $employee->insert($data) ){
            $code = 200;
            $message  = 'Successfully created employee!';
        } else{
            $code = 203;
            $message  = 'Unable to create employee.';
        }

        $this->send_response($message, [], $code);

    }

    public function update() {
        $employee = new Employee();
    
        $data = json_decode(file_get_contents("php://input"));
        $employee->id = $data->id;

        if( $employee->update( $data ) ){
            $code = 200;
            $message  = 'Employee successfuly updated!';
        } else{
            $code = 203;
            $message  = 'Unable to update employee.';
        }

        $this->send_response($message, $employee->id, $code);
    }

    public function delete() {
        $employee = new Employee();
    
        $data = json_decode(file_get_contents("php://input"));        
        $employee->id = $data->id;

        if( $employee->delete( $employee->id ) ){
            $code = 200;
            $message  = 'Employee successfuly deleted!';
        } else {
            $code = 203;
            $message  = 'Unable to delete employee.';
        }

        $this->send_response($message, $employee->id, $code);
    }

     private function send_response( $message, $data, $code = 0 ) {
        
        http_response_code($code);

        $response = new stdClass();
        $response->message = $message;
        $response->code = $code;
        $response->data = $data;

        echo json_encode($response);
    }

}

?>