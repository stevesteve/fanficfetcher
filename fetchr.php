<?php
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
	try{		
		$resultAdapter = $af->createAdapter($request["url"], __DIR__ . "/tmp/" . $dlid);
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

	$resultAdapter->fetch();



	$response["status"] = 1;
	$response["msg"] = "";
	$response["dlid"] = $dlid;
	$response["fname"] = $resultAdapter->getAuthor() . " - ".$resultAdapter->getFanficTitle().".epub";
	die(json_encode($response));





?>