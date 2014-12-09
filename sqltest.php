<?php

	$dba = new SQLite3("jobs.db");
	$dba->exec("create table job(name)");
?>