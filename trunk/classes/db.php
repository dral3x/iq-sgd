<?php

require_once (dirname(__FILE__) . '/../config/db.php'); // DBConfig

class DBConnector {
    
    private $attiva = false;
    
    public function connect() {
    	if (!$this->attiva) {
    		$connessione = mysql_connect(DBConfig::hostname, DBConfig::username, DBConfig::password);
    		if (!$connessione) {
    			die('Could not connect: ' . mysql_error());
			}
			
    		$selezione = mysql_select_db(DBConfig::name, $connessione);
    		if (!$selezione) {
    			die('Could not select db: ' . mysql_error());
			}
			$attiva = true;
    	}
    	return true;
    }

	public function disconnect() {
		if ($this->attiva) {
			if (mysql_close()) {
				$this->attiva = false;
				return true;
			} else {
				return false;
			}
		}
	}  
	
	public function query($sql) {
		if (isset($this->attiva)) {
			$result = mysql_query($sql);
			if (!$result) {
				die('Could not execute query: ' . mysql_error());
			}
			return $result;
		} else {
			return false;
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
			$objs = mysql_fetch_object($result);
			return $objs;
		} else {
			return false;
		}
	}
}

?>