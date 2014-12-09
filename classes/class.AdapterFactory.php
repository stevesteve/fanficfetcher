<?php

	/**
	* 
	*/
	class AdapterFactory
	{
		private $matchmap = array(
			"^(http:\/\/|https:\/\/)?(www\.)?fanfiction.net" => "FanfictionnetAdapter"
			);


		function createAdapter($url,$outputDir)
		{
			foreach ($this->matchmap as $key => $value) {
				if(preg_match("/".$key."/", $url)){
					return new $value($url,$outputDir);
				}
			}

			throw new UnsupportedFanficProviderException();
		}



		
	}
?>