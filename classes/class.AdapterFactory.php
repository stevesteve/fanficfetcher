<?php

	/**
	* 
	*/
	class AdapterFactory
	{
		private $matchmap = array(
			"^(http:\/\/|https:\/\/)?(www\.)?fanfiction.net" => "FanfictionnetAdapter"
			);

		private $protocolMap = array(
			"FanfictionnetAdapter" => "https://"
			);

		function createAdapter($jobId, $url,$outputDir, $dbaccess)
		{
			foreach ($this->matchmap as $key => $value) {
				if(preg_match("/".$key."/", $url)){
					return new $value($jobId, $this->protocolMap[$value].$url,$outputDir,$dbaccess);
				}
			}

			throw new UnsupportedFanficProviderException("Ungültiger Fanficprovider: ".$url);
		}



		
	}
?>