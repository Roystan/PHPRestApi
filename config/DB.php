<?php 

	include('setup.php');

    class DB {

		// db connection config vars
        private $user = DBUSER;
        private $pass = DBPWD;
        private $dbName = DBNAME;
        private $dbHost = DBHOST;
		
        public $conn;
		
		public function __construct() {
			$this->conn = $this->getConnection();
		}

        public function getConnection(){
            try{
				$this->conn = new PDO('pgsql:host=' . $this->dbHost . ';dbname=' . $this->dbName . ';sslmode=require', $this->user, $this->pass);
                $this->conn->exec("set names utf8");
            }catch(PDOException $exception){
                echo "Database could not be connected: " . $exception->getMessage();
            }
            return $this->conn;
        }
    }  
?>