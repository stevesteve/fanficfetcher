<?php

if(!isset($_POST["dlid"]))
	header("location: index.php");



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

unlink($file);

?>