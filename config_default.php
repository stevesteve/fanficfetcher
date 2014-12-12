<?php


	/*
		DATABASE SETTINGS
	 */
	define("DB_CONNECTION_STRING","mysql:host=localhost;dbname=database"); // connection string used for PBO.
	define("DB_USER","root"); // database username
	define("DB_PASS","root"); // database password

	/*
		OTHER SETTINGS
	 */
	define("SITE_ROOT", __DIR__);
	define("TEMP_EPUB_DIR", SITE_ROOT . "/tmp/"); // the epubs get stored there, then deleted once downloaded.


?>