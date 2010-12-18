<?php

require_once('config.php');

class DBConnector {
    
    private $attiva = false;
    
    public function connect() {
    	if (!$this->attiva) {
    		$connessione = mysql_connect($db_hostname, $db_username, $db_password) or die (mysql_error());
    		mysq_select_db($db_database);
    		$selezione = mysql_select_db($db_name, $connessione) or die (mysql_error());
    	} else {
    		return true;
    	}
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
			$sql = mysql_query($sql) or die(mysql_error());
			return $sql;
		} else {
			return false;
		}
	}
	
	public function rows($result) {
		return mysql_num_rows($result);
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