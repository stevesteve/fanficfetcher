<?php
	require_once __DIR__ . "/config.php";
	$request = $_POST;
	if(!isset($request["id"]))
		die();

	$dbhandle = new PDO(DB_CONNECTION_STRING,DB_USER,DB_PASS);
	$statement = $dbhandle->prepare("SELECT filename FROM job WHERE id=:id");
	$statement->bindValue(":id", $request["id"]);
	$statement->execute();
	$result = $statement->fetch(PDO::FETCH_OBJ);

	$file = __DIR__ . "/tmp/" . $request["id"];

	if(!file_exists($file))
		header("location: index.php");

	header("content-type: application/octet-stream");
	header('Content-Disposition: attachment; filename="'.$result->filename.'".epub');
	readfile($file);
	unlink($file)
?>