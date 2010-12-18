<?php

include_once (dirname(__FILE__) . '/../config/db.php');

class DBConnector {
    
    private $attiva = false;
    
    public function connect() {
    	if (!$this->attiva) {
    		$connessione = mysql_connect(DBConfig::$db_hostname, DBConfig::$db_username, DBConfig::$db_password);
    		if (!$connessione) {
    			die('Could not connect: ' . mysql_error());
			}
			
    		$selezione = mysql_select_db(DBConfig::$db_name, $connessione);
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