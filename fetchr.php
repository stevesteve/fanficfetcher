<?php
	ob_end_clean();
	header("Connection: close");
	ignore_user_abort(true); // just to be safe
	ob_start();
	require_once __DIR__ . "/classes/autoload.php";
	require_once __DIR__ . "/classes/vendor/HTMLPurifier/HTMLPurifier.auto.php";
	header("Content-type: application/json");

	$response = array();
	$request = $_POST;
	if(!isset($request["url"]) || $request["url"] == "")
	{
		$response["status"] = -1;
		$response["msg"] = "Leerer Fanfic provider.";
		die(json_encode($response));
	}
	$af = new AdapterFactory();
	$resultAdapter = "";
	$dlid = round(microtime(1),0);


	/*$response["status"] = -1;
	$response["msg"] = __DIR__ . "/jobs.db";
	die(json_encode($response));
*/
	try{		
		$resultAdapter = $af->createAdapter($request["url"], __DIR__ . "/tmp/", __DIR__ . "/jobs.db");
	} catch (UnsupportedFanficProviderException $ex)
	{
		$response["status"] = -1;
		$response["msg"] = "Unbekannter Fanfic provider: ".$request["url"];
		die(json_encode($response));
	} catch (Exception $exc)
	{

		$response["status"] = -1;
		$response["msg"] = $exc->getMessage();
		die(json_encode($response));
	}

	



	$response["status"] = 1;
	$response["msg"] = "";
	$response["dlid"] = $resultAdapter->getJobId();
	$response["fname"] = $resultAdapter->getAuthor() . " - ".$resultAdapter->getFanficTitle().".epub";
	echo(json_encode($response));
	$size = ob_get_length();
	header("Content-Length: $size");
	ob_end_flush(); // Strange behaviour, will not work
	flush(); // Unless both are called !

	$resultAdapter->fetch();





?>