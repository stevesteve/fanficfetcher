<?php
	header("Content-type: application/json");
	require_once __DIR__ . "/config.php";
	require_once __DIR__ . "/classes/autoload.php";
	require_once __DIR__ . "/classes/vendor/HTMLPurifier/HTMLPurifier.auto.php";

	$request = $_POST;
	if(!isset($request["id"]))
		header("location: index.php");



	$dbhandle = new PDO(DB_CONNECTION_STRING,DB_USER,DB_PASS);
	$statement = $dbhandle->prepare("SELECT url FROM job WHERE id=:id");
	$statement->bindValue(":id",$request["id"]);
	$statement->execute();
	$result = $statement->fetch(PDO::FETCH_OBJ);

	$af = new AdapterFactory();
	$resultAdapter = "";
	try{		
		$resultAdapter = $af->createAdapter($request["id"], $result->url, TEMP_EPUB_DIR, $dbhandle);
	} catch (Exception $ex)
	{
		$response["status"] = -1;
		$response["msg"] = "Unbekannter Fanfic provider: ".$result->url;
		die(json_encode($response));
	}

try{	

	$resultAdapter->fetch();
} catch(Exception $ex){
	$response["status"] = -1;
	$response["msg"] = $ex->getMessage();
	die(json_encode($response));
}

	



/*
$dbaccess = new SQLite3("jobs.db");
$dbaccess->busyTimeout(-1);

$statement = $dbaccess->prepare("SELECT filename FROM job WHERE id=:id");
$statement->bindValue(":id",$_POST["dlid"]);
$result = $statement->execute()->fetchArray(SQLITE3_ASSOC); 

$file = __DIR__ . "/tmp/" . $_POST["dlid"];

if(!file_exists($file))
	header("location: index.php");

header("content-type: application/octet-stream");
header('Content-Disposition: attachment; filename='.$result["filename"].".epub");
readfile($file);

unlink($file);*/

?>