<?php

require_once (dirname(__FILE__) . '/../config/db_config.php'); // DBConfig

class DBConnector {
    
    private $attiva = false;
    private $connessione = false;
    private $error_message;
    
    public function connect() {
    	if (!$this->attiva) {
    		$this->connessione = mysql_connect(DBConfig::hostname, DBConfig::username, DBConfig::password);
    		if (!$this->connessione) {
    			die('Could not connect: ' . mysql_error());
			}
			
    		$selezione = mysql_select_db(DBConfig::name, $this->connessione);
    		if (!$selezione) {
    			die('Could not select db: ' . mysql_error());
			}
			$attiva = true;
    	}
    	return true;
    }

	public function disconnect() {
		if ($this->attiva) {
			if (mysql_close($this->connessione)) {
				$this->attiva = false;
				return true;
			} else {
				return false;
			}
		}
	}  
	
	public function query($sql, $skip_die = false) {
		if (isset($this->attiva)) {
			$result = mysql_query($sql, $this->connessione);
			if (!$result) {
				if ($skip_die) {
					$this->error_message = 'Could not execute query: ' .$sql. ' <br/><br/>*error type:' . mysql_error();
					//$this->error_message = 'Could not execute query: ' . mysql_error();
				
				} else {
					die('Could not execute query: ' .$sql. ' <br/><br/>*error type:' . mysql_error());
					//die('Could not execute query: ' . mysql_error());
				}
			}
			
			//die('executing query: ' .$sql);
			return $result;
		} else {
			return false;
		}
	}
	
	public function begin_transaction() {
		if (isset($this->attiva)) {
			mysql_query("BEGIN", $this->connessione);
		}
		
	}

	public function rollback_transaction() {
		if (isset($this->attiva)) {
			mysql_query("ROLLBACK", $this->connessione);
		}
		
	}
	
	public function commit_transaction() {
		if (isset($this->attiva)) {
			mysql_query("COMMIT", $this->connessione);
		}
		
	}
	
	public function rows($result) {
		if (isset($this->attiva)) {
			return mysql_num_rows($result);
		} else {
			return false;
		}
	}
	
	public function extract_object($result) {
		if (isset($this->attiva)) {
			return mysql_fetch_object($result);
		} else {
			return false;
		}
	}
	
	public function getErrorMessage() {
		if (isset($this->error_message)) {
			return $this->error_message;
		} else {
			return false;
		}
	}
}

?>