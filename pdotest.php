<?php


error_reporting(E_ALL);

$dbhandle = new PDO("mysql:host=localhost;dbname=fetchr", "root", "");
$dbhandle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$resultSet = $dbhandle->query("select * from job");

//print_r($resultSet);

$data = $resultSet->fetch(PDO::FETCH_ASSOC);
print_r($data);

?>