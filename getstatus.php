<?php
	$request = array_merge($_POST, $_GET);
	if(!isset($request["id"]))
		die();
	$dbaccess = new SQLite3("jobs.db");
	$dbaccess->busyTimeout(-1);
	$lastTimestamp = round(microtime(1),0);
	while(true)
	{

		
		$statement = $dbaccess->prepare("SELECT currentChapter, totalChapters, timestamp FROM job WHERE id=:id");	

		
		$statement->bindValue(":id",$request["id"]);
		$result = $statement->execute()->fetchArray(SQLITE3_ASSOC);


		if($result["timestamp"] > $lastTimestamp || $result["currentChapter"] == $result["totalChapters"])
		{
			die(json_encode($result));
		}

		sleep(1);
	}
	
	
	//

?>