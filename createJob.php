<?php
	header("Content-type: application/json");
	require_once __DIR__ . "/vendor/autoload.php";
	$whoops = new \Whoops\Run;
	$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
	$whoops->register();
	require_once __DIR__ . "/config.php";
	require_once __DIR__ . "/classes/autoload.php";
	require_once __DIR__ . "/classes/vendor/HTMLPurifier/HTMLPurifier.auto.php";
	$response = array();
	$request = array_merge($_POST, $_GET);
	
	if(!isset($request["url"]) || $request["url"] == "")
	{
		$response["status"] = -1;
		$response["msg"] = "Leerer Fanfic provider.";
		die(json_encode($response));
	}


	$dbhandle = new PDO(DB_CONNECTION_STRING,DB_USER,DB_PASS);
	$statement = $dbhandle->prepare("INSERT INTO job VALUES(null, null, -1, 0, :url, null)");
	$statement->bindValue(":url", $request["url"]);
	$statement->execute();
	
	$response["status"] = 1;
	$response["msg"] = "";
	$response["dlid"] = $dbhandle->lastInsertId();
	die(json_encode($response));


?>
