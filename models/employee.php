<?php

    include_once 'core/my_model.php';

    class Employee extends MyModel{

        // Table
        protected $table = "employee";
        public $primaryKey = "id";

        // Columns
        public $id;
        public $name;
        public $email;
        public $age;
        public $designation;
        public $created;

        public $field_arr = [
            'name'          => array('type' => self::TYPE_INT,      'validate' => 'isInt',  'size' => 11),
			'email'         => array('type' => self::TYPE_STRING,   'required' => true,     'validate' => 'isString', 'size' => 32),
			'age'           => array('type' => self::TYPE_STRING,   'required' => true),
			'designation'   => array('type' => self::TYPE_FLOAT,    'required' => true),
			'created'       => array('type' => self::TYPE_DATE,     'validate' => 'isDate'),
        ];

        public function __construct () {
            parent::__construct();
        }

    }
?>

