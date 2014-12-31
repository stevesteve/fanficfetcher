<?php

abstract class Adapter
{	
	/**
	 * URL to the specific fanfic
	 * @var string
	 */
	protected $url;

	/**
	 * Total chapter count
	 * @var integer
	 */
	protected $chapterCount = -1;

	/**
	 * The HTML document of the first chapter. used to get author name, fanfic title etc.
	 * @var DOMDocument
	 */
	protected $document;

	/**
	 * Fanfic ID, given by the fanfic service.
	 * @var integer
	 */
	protected $fanficID = -1;

	/**
	 * The name of the Fanfic.
	 * @var string
	 */
	protected $fanficTitle;

	/**
	 * The name of the Author.
	 * @var string
	 */
	protected $author = "";

	/**
	 * URL to the cover image.
	 * @var string
	 */
	protected $coverImage = "";

	/**
	 * The epub container
	 * @var EPub
	 */
	protected $epub;

	/**
	 * PDO access to the Database
	 * @var PDO
	 */
	protected $dbaccess;

	/**
	 * Download Job ID
	 * @var integer
	 */
	protected $jobId;

	/**
	 * The base URL of the Fanfic provider
	 * @var string
	 */
	protected $baseURL;

	/**
	 * Links to a specific Chapter when given the chapter number.
	 * @var string
	 */
	protected $navigationPattern;

	/**
	 * Epub chapter beginning tag
	 * @var string
	 */
	protected $CHAPTER_START ="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n<head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n<title>Test Book</title>\n</head>\n<body>\n";

	/**
	 * Epub chapter ending tag
	 * @var string
	 */
	protected $CHAPTER_END = "</body>\n</html>\n";

	function __construct($jobId, $url, $outputDir, $dbaccess)
	{
		/*try{
			$headers = get_headers($url);	
		} catch(Exception $ex){
			throw new AdapterException("Unbekannter Fehler beim überprüfen der Website: ".$ex->getMessage());
		}
		
		if (strpos($headers[0],'404') !== false) {
			throw new AdapterException("Fehler 404: Folgende URL wurde nicht gefunden: ".$url);
		}*/

		$this->jobId = $jobId;
		$this->url = $url;
		$this->document = new DOMDocument();
		@$this->document->loadHTMLFile($url);	
		$this->dbaccess = $dbaccess;
		$this->epub = new EPub( EPub::BOOK_VERSION_EPUB2, "en", EPub::DIRECTION_LEFT_TO_RIGHT, $outputDir.$this->jobId);
		$this->initJobEntry();
	}

	abstract function fetch();
	abstract protected function fetchAuthor();
	abstract protected function fetchChapterCount();
	abstract protected function fetchFanficTitle();
	abstract protected function fetchCoverImage();

	/**
	 * Initialize the Database entry with some values.
	 * @author XepherX
	 * @return void
	 */
	function initJobEntry(){
		$statement = $this->dbaccess->prepare("UPDATE job SET totalChapters = :totalChapters, filename=:filename WHERE id = :id");
		$statement->bindValue(":totalChapters", $this->getChapterCount());
		$statement->bindValue(":filename", $this->getAuthor()." - ".$this->getFanficTitle());
		$statement->bindValue(":id", $this->jobId);
		$statement->execute();
	}

	/**
	 * Update the current Progress in the Database
	 * @author XepherX
	 * @param  int $currentChapter 
	 * @return void
	 */
	function updateProgress($currentChapter){
		$statement = $this->dbaccess->prepare("UPDATE job SET currentChapter=:currentChapter WHERE id=:id");
		$statement->bindValue(":currentChapter",$currentChapter);
		$statement->bindValue(":id",$this->jobId);
		$statement->execute();
	}

	function getJobId(){
		return $this->jobId;
	}
	
	function getChapterCount(){ 
		if($this->chapterCount === -1)
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