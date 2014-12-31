<?php
	header("Content-type: application/json");
	require_once __DIR__ . "/config.php";
	$request = array_merge($_POST, $_GET);
	if(!isset($request["id"]))
		die();
	$dbaccess = new PDO(DB_CONNECTION_STRING,DB_USER,DB_PASS);
	
	$lastTimestamp = time();
	while(true)
	{
		$statement = $dbaccess->prepare("SELECT currentChapter, totalChapters, timestamp FROM job WHERE id=:id");	
		$statement->bindValue(":id",$request["id"]);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		if(strtotime($result["timestamp"]) > $lastTimestamp || $result["currentChapter"] == $result["totalChapters"])
		{
			die(json_encode($result));
		}
		sleep(1);
	}

?>