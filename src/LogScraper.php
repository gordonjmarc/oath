<?php


/**
 * An enty with data captured from LogScraper
 */
class LogEntry
{
	public $queryTime = "";
	public $lockTime = "";
	public $rowsSent = "";
	public $bytesSent = "";
	public $rowsExamined = "";
	public $rowsEffected = "";
	public $timeStamp = "";
	public $query="";
	public $queryType = "";
}

/**
 * Collection of LogEntries, and Reporting Methods
 */
class LogReport
{
	public $entries = array();

	public function addEntry($le) 
	{
		//TODO: validate entry

		$this->entires[] = $le;
	}

	public function printSummaryReport() 
	{
		//TODO
	}

}


/**
 * Parses contents of whole log, creates log report
 */
class LogScraper
{
	public $file;
	public $contents;

	public $entryPattern=  "/# User@Host: /"; 
	public $queryTimePattern = "/Query_time: (([0-9]|\.)*)/";
	public $lockTimePattern = "/Lock_time: (([0-9]|\.)*)/";
	public $rowsSentPattern = "/Rows_sent: (([0-9])*)/";
	public $bytesSentPattern = "/Bytes_sent: (([0-9])*)/";
	public $rowsExaminedPattern = "/Rows_examined: (([0-9])*)/";
	public $rowsEffectedPattern = "/Rows_affected: (([0-9])*)/";
	public $timeStampPattern = "/SET timestamp=(([0-9])*)/";

	public $logReport;


	public function __construct($file)
	{
		if (!file_exists($file)) 
		{
			throw new FileNotFoundException("File not found");
		}

		$this->file = new \SplFileInfo($file);
		$contents = $this->file->openFile("r");
		$this->contents = $contents->fread($this->file->getSize());

		$this->logReport = new LogReport();
	}

	public function parseEntries() 
	{

		$rawEntries = preg_split($this->entryPattern, $this->contents, -1, PREG_SPLIT_NO_EMPTY);
		
		foreach($rawEntries as $rawEntry) 
		{
			$le = new LogEntry();

	 		if(preg_match($this->queryTimePattern, $rawEntry, $matches) ) 
			{
				$le->queryTime= $matches[1];
			}
			
			if(preg_match($this->lockTimePattern, $rawEntry, $matches) ) 
			{
				$le->lockTime = $matches[1];
			}

			if(preg_match($this->rowsSentPattern, $rawEntry, $matches) ) 
			{
				$le->rowsSent = $matches[1];
			}

			if(preg_match($this->bytesSentPattern, $rawEntry, $matches) ) 
			{
				$le->bytesSent = $matches[1];
			}

			if(preg_match($this->rowsExaminedPattern, $rawEntry, $matches) ) 
			{
				$le->rowsExamined = $matches[1];
			}

			if(preg_match($this->rowsEffectedPattern, $rawEntry, $matches) ) 
			{
				$le->rowsEffected = $matches[1];
			}

			if(preg_match($this->timeStampPattern, $rawEntry, $matches) ) 
			{
				$le->timeStamp = $matches[1];
			}

			if(stripos($rawEntry, ';') > 0) //query exists between semi colons
			{
				$le->query = trim ( substr($rawEntry, stripos($rawEntry, ';')+1 ) );
				$le->queryType = substr($le->query, 0 , stripos($le->query, ' '));
			}

			$this->logReport->addEntry($le);
		}

		return $this->logReport;
	}

}

$file = "";
$startTime = "";
$endTime = ""; 

if(!empty($argv[1]))
{
	$file = $argv[1];
}

if(!empty($argv[2]))
{
	$startTime = $argv[2];
}

if(!empty($argv[3]))
{
	$endTime = $argv[3];
}


if(empty($file)) 
{
	throw new InvalidArgumentException("arg 1 must be a file");
}

//TODO: handle timestamp inputs

$ls = new LogScraper($file);
$lr = $ls->parseEntries();

//var_dump($lr);



?>