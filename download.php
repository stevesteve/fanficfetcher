<?php

if(!isset($_POST["dlid"]))
	header("location: index.php");

$file = __DIR__ . "/tmp/" . $_POST["dlid"];

if(!file_exists($file))
	header("location: index.php");

header("content-type: application/octet-stream");
header('Content-Disposition: attachment; filename='.$_POST["fname"]);
readfile($file);

unlink($file);

?>