<?php
	require_once(dirname(dirname(__FILE__)) . "/config/config.php");
//include('./config.php');

class Database {
		public static $instance;
		private $mysqli,
				$query,
				$results,
			  $count = 0;
	 
		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new Database();
			}
			return self::$instance;
		}
			
		public function __construct() {
			$this->mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
      $this->mysqli->set_charset("utf8");
      //mysqli_set_charset( $this->mysqli->connect, 'utf8');

			if ($this->mysqli->connect_error) {
				die('Failed: '. $this->mysqli->connect_error);
			}
		}
    
    public function insert($qry) {
        $result = $this->mysqli->query($qry);
        return $result;
    }
    
    public function query($sql) {
			if ($this->query = $this->mysqli->query($sql)) {
        
        while ($row = $this->query->fetch_assoc()) {
					$this->results[] = $row;
				}
        
        $this->count = $this->query->num_rows;
			}
			return $this;
		}
		
		public function results() {
			return $this->results;
		}

		public function count() {
			return $this->count;
		}
    
    public function insert_id(){
      return $this->mysqli->insert_id;
    }   

    public function close(){
      return $this->mysqli->close();
    }   
}
