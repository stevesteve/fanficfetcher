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

		function createAdapter($url,$outputDir,$dbDir)
		{
			foreach ($this->matchmap as $key => $value) {
				if(preg_match("/".$key."/", $url)){
					return new $value($this->protocolMap[$value].$url,$outputDir,$dbDir);
				}
			}

			throw new UnsupportedFanficProviderException();
		}



		
	}
?>