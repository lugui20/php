<?php
class IntDB
{
	private $pdo_conn_string = "pgsql:host=localhost port=5434 dbname=postgres user=postgres password=mylove";
	private $counter = 0;
	
	public function pdo_db_connect()
	{

		try
		{
			$pdo = @new PDO($this->pdo_conn_string);
			
		}
		catch (RuntimeException $e)
		{
			$GLOBALS["error"]= $e->getMessage();
			return null;
		}
		return $pdo;
	}
	
	public function pdo_db_prepare($pdo,$query)
	{
		return $pdo->prepare($query);
	}
	
	public function pdo_db_bindValue(&$pQuery, $name, $value)
	{
		$pQuery->bindValue(":" . $name, $value);
		return;
	}

	public function pdo_db_bindValueBool(&$pQuery, $name, $value)
	{
		$pQuery->bindValue(":" . $name, $value, PDO::PARAM_BOOL);
		return;
	}
	
	public function pdo_db_bindValueString(&$pQuery, $name, $value)
	{
		$pQuery->bindValue(":" . $name, $value, PDO::PARAM_STR);
		return;
	}

	public function pdo_db_query($pQuery)
	{
		return $pQuery->execute();	
	}
	
	public function pdo_db_fetch($pQuery)
	{
		return $pQuery->fetch(PDO::FETCH_NUM);	
	}
	
	public function pdo_db_fetch_assoc($pQuery)
	{
		return $pQuery->fetch(PDO::FETCH_ASSOC);	
	}
	
	public function pdo_db_fetch_all($pQuery)
	{
		return $pQuery->fetchAll(PDO::FETCH_ASSOC);	
	}
	
	public function pdo_db_columnCount($pQuery)
	{
		return $pQuery->columnCount();
	}
	
	public function pdo_db_beginTransaction($pdo)
	{
		if (!$this->counter++)
		{
			return $pdo->beginTransaction();
		}
		$query =  "SAVEPOINT trans" . $this->counter;
		$pQuery = $this->pdo_db_prepare($pdo, $query);
		$this->pdo_db_query($pQuery);
		return $this->counter >= 0;
		
	}
	
	public function pdo_db_rollBack($pdo)
	{
		if (--$this->counter)
		{
		  	$query =  "ROLLBACK TO trans" . $this->counter;
			$pQuery = $this->pdo_db_prepare($pdo, $query);
			$this->pdo_db_query($pQuery);
            return true;
        }
        return $pdo->rollBack();		
	}
	
	public function pdo_db_commit($pdo)
	{
		if (!--$this->counter)
		{
            return $pdo->commit();
        }
        return $this->counter >= 0;
	}

	public function pdo_db_showErrors(&$pQuery)
	{	
		$arr = $pQuery->errorInfo();
		print_r($arr);
		return;
	}
	
	public function pdo_db_rowCount($pQuery)
	{
		$count = $pQuery->rowCount();
		return $count;
	}
	
	
	
}
?>