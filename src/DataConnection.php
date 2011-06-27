<?php

class DataConnection {
	
	private $host;
	private $user;
	private $pass;
	private $dbaseName;
	private $connection;
	
	public function __construct($dbHost, $dbUser, $dbPass, $dbDBaseName){
		
		$this->host = $dbHost;
		$this->user = $dbUser;
		$this->pass = $dbPass;
		$this->dbaseName = $dbDBaseName;
		
		$this->_init();
		
	}
	
	public function __destruct(){
		mysql_close($this->connection);
	}
	
	private function _init(){
		$this->connection = mysql_connect($this->host, $this->user, $this->pass) or die('Unable to connect to the database');
		mysql_select_db($this->dbaseName, $this->connection) or die('Unable to select database from host');
	}
	
	public function selectFromQuery($colToSelect, $tableToSelectFrom, $options){
		
		$query = "SELECT {$colToSelect} FROM {$tableToSelectFrom}";
		if(!is_null($options)){
			$query.=" {$options}";
		}
		$result = mysql_query($query, $this->connection) or die(mysql_error());
		if(mysql_num_rows($result) == 0){
			return false;
		} else {
			return $result;
			
		}
		
	}
	
	public function selectFromWhereQuery($colToSelect, $tableToSelectFrom, $colToMatch, $matchType, $valueToMatch, $options){
		
		$query = "SELECT {$colToSelect} FROM {$tableToSelectFrom} WHERE {$colToMatch} {$matchType} '{$valueToMatch}'";
		if(!is_null($options)){
			$query.=" {$options}";
		}
		$result = mysql_query($query, $this->connection) or die(mysql_error());
		if(mysql_num_rows($result) != 0){
			return $result;
		} else {
			return false;
		}
		
	}
	
	public function selectFromWhereNotQuery($colToSelect, $tableToSelectFrom, $colToMatch, $matchType, $valueToMatch, $options){
		
		$query = "SELECT {$colToSelect} FROM {$tableToSelectFrom} WHERE NOT {$colToMatch}{$matchType}'{$valueToMatch}'";
		if(!is_null($options)){
			$query.=" {$options}";
		}
		$result = mysql_query($query, $this->connection) or die(mysql_error());
		if(mysql_num_rows($result) > 0){
			return $result;
		} else {
			return false;
		}
		
	}
	
	public function selectFromWhereIn($colToSelect, $tableToSelectFrom, $colToMatch, $inValues, $options){
		
		$query = "SELECT {$colToSelect} FROM {$tableToSelectFrom} WHERE {$colToMatch} IN (";
		for($i = 0; $i < count($inValues) - 1; $i++){
			$query.= $inValues[$i];
			if($i < count($inValues) - 1){
				$query.= ", ";
			} 
		}
		$query.= ")";
		if(!is_null($options)){
			$query.=" {$options}";
		}
		
		$result = mysql_query($query, $this->connection) or die(mysql_error());
		if(mysql_num_rows($result) > 0){
			return $result;
		} else {
			return false;
		}
	}
	
	public function insertIntoQuery($tableToInsertInto, $keyValuePairs, $options){
		
		$keys = array();
		$values = array();
		foreach($keyValuePairs as $key => $value){
			array_push($keys, $key);
			array_push($values, $value);
		}
		
		$query = "INSERT INTO {$tableToInsertInto} ";
		$query.="(";
		
		$i = 0;
		while($i < sizeof($keys)){
			$query.=" {$keys[$i]}";
			if($i < sizeof($keys) - 1){
				$query.=",";
			}
			$i += 1;
		}
		
		$query.=" ) VALUES (";
		
		$j = 0;
		while($j < sizeof($values)){
			$query.=" '{$values[$j]}'";
			if($j < sizeof($values) - 1){
				$query.=",";
			}
			$j += 1;
		}
		
		$query.=" )";
		if(!is_null($options)){
			$query.=" {$options}";
		}
		
		$result = mysql_query($query, $this->connection) or die(mysql_error());
		if(mysql_affected_rows($this->connection) > 0){
			return $result;
		} else {
			return false;
		}
	}
	
	public function updateQuery($tableToUpdate, $keyValueUpdatePairs, $options){
		
		$query = "UPDATE {$tableToUpdate}";
		
		$counter = 0;
		$query .= " SET";
		foreach($keyValueUpdatePairs as $key=>$value){
			$query .= " {$key}='{$value}'";
			if($counter < count($keyValueUpdatePairs) - 1){
				$query .= ",";
			}
			$counter += 1;
		}
		
		if(!is_null($options)){
			$query.=" {$options}";
		}
		
		$result = mysql_query($query, $this->connection) or die(mysql_error());;
		if(mysql_affected_rows($this->connection) > 0){
			return $result;
		} else {
			return false;
		}
		
	}
	
	public function deleteFromWhereQuery($tableToDeleteFrom, $colToMatch, $matchType, $valueToMatch, $options){
		
		$query = "DELETE FROM {$tableToDeleteFrom} WHERE {$colToMatch}{$matchType}'{$valueToMatch}'";
		if(!is_null($options)){
			$query.=" {$options}";
		}
		echo $query;
		$result = mysql_query($query, $this->connection) or die(mysql_error());
		if(mysql_affected_rows($this->connection)){
			return $result;
		} else {
			return false;
		}
	}
	
	public function deleteAllRowsFromTable($tableName){
		
		$query = "DELETE FROM {$tableName}";
		$result = mysql_query($query, $this->connection) or die(mysql_error());
		if(mysql_affected_rows($this->connection) > 0){
			return $result;
		} else {
			return false;
		}
		
	}
	
	
}

?>