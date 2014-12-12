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
	protected $dbaccess;
	protected $jobId;


	protected $CHAPTER_START ="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n<head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n<link rel=\"stylesheet\" type=\"text/css\" href=\"styles.css\" />\n<title>Test Book</title>\n</head>\n<body>\n";


	protected $CHAPTER_END = "</body>\n</html>\n";

	function __construct($jobId, $url, $outputDir, $dbaccess)
	{
		$this->jobId = $jobId;
		$this->url = $url;
		$this->document = new DOMDocument();
		@$this->document->loadHTMLFile($url);	
		$this->dbaccess = $dbaccess;
		//$this->dbaccess = new SQLite3($dbDir);
		//$this->dbaccess->busyTimeout(-1);
		
		$this->epub = new EPub( EPub::BOOK_VERSION_EPUB2, "en", EPub::DIRECTION_LEFT_TO_RIGHT, $outputDir.$this->jobId);
		$this->initJobEntry();
	}

	abstract function fetch();
	abstract protected function fetchAuthor();
	abstract protected function fetchChapterCount();
	abstract protected function fetchFanficTitle();
	abstract protected function fetchCoverImage();

	function initJobEntry(){
		$statement = $this->dbaccess->prepare("UPDATE job SET totalChapters = :totalChapters, filename=:filename WHERE id = :id");
		$statement->bindValue(":totalChapters", $this->getChapterCount());
		$statement->bindValue(":filename", $this->getAuthor()." - ".$this->getFanficTitle());
		$statement->bindValue(":id", $this->jobId);
		$statement->execute();
		//echo("set ".$this->jobId." to ".$this->getChapterCount());
		/*$statement = $this->dbaccess->prepare("INSERT INTO job VALUES(null, :timeStamp, :chapterCount, 0, :fileName)");
		$statement->bindValue(':timeStamp',round(microtime(1),0),SQLITE3_INTEGER);
		$statement->bindValue(':chapterCount',$this->getChapterCount(),SQLITE3_INTEGER);
		$statement->bindValue(':fileName', $this->getAuthor()." - ".$this->getFanficTitle());
		$statement->execute();
		$this->jobId = $this->dbaccess->lastInsertId();*/

	}

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