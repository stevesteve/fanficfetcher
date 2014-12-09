<?php


/**
* 		
*/
abstract class Adapter
{	

	protected $url;
	protected $documents = array();
	protected $cover;
	protected $chapterCount = -1;
	protected $document;
	protected $fanficID = -1;
	protected $fanficTitle;
	protected $author = "";
	protected $coverImage = "";
	protected $chapters;
	protected $epub;


	protected $CHAPTER_START ="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n<head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n<link rel=\"stylesheet\" type=\"text/css\" href=\"styles.css\" />\n<title>Test Book</title>\n</head>\n<body>\n";


	protected $CHAPTER_END = "</body>\n</html>\n";

	function __construct($url, $outputDir)
	{
		$this->url = $url;
		$this->document = new DOMDocument();
		@$this->document->loadHTMLFile($url);	
		$this->epub = new EPub( EPub::BOOK_VERSION_EPUB2, "en", EPub::DIRECTION_LEFT_TO_RIGHT, $outputDir);
	}

	abstract function fetch();
	abstract protected function fetchAuthor();
	abstract protected function fetchChapterCount();
	abstract protected function fetchFanficTitle();
	abstract protected function fetchCoverImage();


	
	function getChapterCount(){ 
		if($this->chapterCount == -1)
			$this->fetchChapterCount();
		return $this->chapterCount; 
	}

	function getAuthor(){
		if($this->author == "")
			$this->fetchAuthor();
		return $this->author;

	}

	function getFanficTitle(){
		if($this->fanficTitle == "")
			$this->fetchFanficTitle();
		return $this->fanficTitle;
	}

	function getCoverImage(){
		if($this->coverImage == "")
			$this->fetchCoverImage();
		return $this->coverImage;
	}

	function download()
	{
		$fileName = str_replace(" ", "_", $this->getFanficTitle()) . "_" . $this->fanficID;
		ob_clean();		
		$zipData = $this->epub->sendBook($fileName);
	}

}



?>