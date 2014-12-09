<?php

/**
 *
 */
class FanfictionnetAdapter extends Adapter {

	protected $baseURL = "http://www.fanfiction.net/s/";
	protected $navigationPattern;
	function __construct($url, $outputDir) {
		parent::__construct($url, $outputDir);

		$matches = array();
		preg_match_all("/^(https:\/\/)?(www\.)?fanfiction.net\/s\/(\\d*)\/(\\d*)\//", $url, $matches);
		
		
		if(count($matches[3]) == 0)
		{
			throw new Exception("Ungültige url: ".$url.". Fanfic ID konnte nicht gelesen werden.");
		}

		
		$this->fanficID = $matches[3][0];

		

		$this->navigationPattern = $this->baseURL . $this->fanficID . "/%d";

	}

	function fetch() {

		$opts = array(
					'http'=>array(
						'header'=>array("Referer: $this->url\r\n")
				)
		);
		$context = stream_context_create($opts);
		$this->epub->setTitle($this->getFanficTitle());
		$this->epub->setIdentifier($this->fanficID, EPub::IDENTIFIER_URI);// Could also be the ISBN number, prefered for published books, or a UUID.
		$this->epub->setLanguage("en");// Not needed, but included for the example, Language is mandatory, but EPub defaults to "en". Use RFC3066 Language codes, such as "en", "da", "fr" etc.
		$this->epub->setDescription("This is a brief description\nA test ePub book as an example of building a book in PHP");
		$this->epub->setAuthor($this->getAuthor(),$this->getAuthor());
		$this->epub->setPublisher("fanfiction.net","fanfiction.net");// I hope this is a non existant address :)
		$this->epub->setDate(time());// Strictly not needed as the book date defaults to time().
		//$this->epub->setRights("Copyright and licence information specific for the book.");// As this is generated, this _could_ contain the name or licence information of the user who purchased the book, if needed. If this is used that way, the identifier must also be made unique for the book.
		$this->epub->setSourceURL($this->url);
		
		
		
		$this->epub->setCoverImage("Cover.jpg", file_get_contents($this->getCoverImage(),false, $context), "image/jpeg");

		$config = HTMLPurifier_Config::createDefault();
		$this->purifier = new HTMLPurifier($config);

		$this->fetchChapters();
		
		$this->epub->finalize();

		
		
		
		
		
	}

	function fetchChapters() {

		$chaptertextQuery = "//*[@id='storytext']";
		
		for ($i = 1; $i <= $this->getChapterCount(); $i++) {

			$currentChapterUrl = sprintf($this->navigationPattern, $i);
			$chapterDocument = new DOMDocument();
			@$chapterDocument->loadHTMLFile($currentChapterUrl);
			
			$xpath = new DOMXpath($chapterDocument);

			$elements = $xpath->query($chaptertextQuery);

			$chapterHtml = "";
			foreach ($elements as $node) {
				$chapterHtml .= $chapterDocument->saveHTML($node);
			}
			
			$chapterHtml = utf8_decode($chapterHtml);
			//$chapterHtml = preg_replace("/hr.*\snoshade/", "hr /",$chapterHtml);
			$chapterHtml = $this->purifier->purify($chapterHtml);
			$chapterHtml = $this->CHAPTER_START . $chapterHtml . $this->CHAPTER_END;
			$this->epub->addChapter("Chapter $i", "Chapter$i.html", $chapterHtml);
			

		}

	}
	function fetchFanficTitle(){
		$xpath = new DOMXpath($this->document);
		$storyNameQuery = "//*[@id='profile_top']/b";
		$elements = $xpath->query($storyNameQuery);
		foreach ($elements as $element) {

			$this->fanficTitle = $element->nodeValue;
			return;
		}
	}
	function fetchAuthor() {
		$xpath = new DOMXpath($this->document);
		$authorQuery = "//*[@id='profile_top']/a[1]";
		$elements = $xpath->query($authorQuery);

		foreach ($elements as $element) {

			$this->author = $element->nodeValue;
			return;
		}
	}
	function fetchChapterCount() {
		$xpath = new DOMXpath($this->document);
		$chapterQuery = "//*[@id='chap_select'][1]";
		$elements = $xpath->query($chapterQuery);

		foreach ($elements as $element) {

			$this->chapterCount = $element->childNodes->length;
			return;
		}
	}
	function fetchCoverImage(){
		$xpath = new DOMXpath($this->document);
		$coverImageQuery = "//*[@id='img_large']/div/img";
		$elements = $xpath->query($coverImageQuery);

		foreach ($elements as $element) {

			foreach($element->attributes as $attribute){
				if($attribute->name == "data-original"){
					$this->coverImage ="https:".$attribute->nodeValue;
					return;
				}
				
			}
		}
	}

}

?>