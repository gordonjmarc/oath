<?php
/**
 * Parses contents of whole log
 */


class LogScraper
{

	protected $file;
	protected $contents;

	protected $entryPattern=  "/# User@Host: /"; 

	protected $queryTimePattern = "/Query_time: (([0-9]|\.)*)/";
	protected $lockTimePattern = "/Query_time: (([0-9]|\.)*)/";
	protected $rowsSentPattern = "/Rows_sent: (([0-9])*)/";
	protected $bytesSentPattern = "/Bytes_sent: (([0-9])*)/";
	protected $rowsExaminedPattern = "/Rows_examined: (([0-9])*)/";
	protected $rowsEffectedPattern = "/Rows_affected: (([0-9])*)/";
	protected $timeStampPattern = "/SET timestamp=(([0-9])*)/";


	public function __construct($file)
	{
		if (!file_exists($file)) 
		{
			throw new FileNotFoundException("File not found");
		}

		$this->file = new \SplFileInfo($file);
		$contents = $this->file->openFile("r");
		$this->contents = $contents->fread($this->file->getSize());
	}

	public function parseEntries() 
	{

		$entries = preg_split($this->entryPattern, $this->contents, -1, PREG_SPLIT_NO_EMPTY);

		foreach($entries as $entry) 
		{
			$queryTime = "";
			$lockTime = "";
			$rowsSent = "";
			$bytesSent = "";
			$rowsExamined = "";
			$rowsEffected = "";
			$timeStamp = "";
			$query="";
			$queryType = "";

	 		if(preg_match($this->queryTimePattern, $entry, $matches) ) 
			{
				$queryTime= $matches[1];
			}
			
			if(preg_match($this->lockTimePattern, $entry, $matches) ) 
			{
				$lockTime = $matches[1];
			}

			if(preg_match($this->rowsSentPattern, $entry, $matches) ) 
			{
				$rowsSent = $matches[1];
			}

			if(preg_match($this->bytesSentPattern, $entry, $matches) ) 
			{
				$bytesSent = $matches[1];
			}

			if(preg_match($this->rowsExaminedPattern, $entry, $matches) ) 
			{
				$rowsExamined = $matches[1];
			}

			if(preg_match($this->rowsEffectedPattern, $entry, $matches) ) 
			{
				$rowsEffected = $matches[1];
			}

			if(preg_match($this->timeStampPattern, $entry, $matches) ) 
			{
				$timeStamp = $matches[1];
			}

			if(stripos($entry, ';') > 0) //query exists between semi colons
			{
				$query = substr($entry, stripos($entry, ';'));
				$queryType = substr($query, 0 , stripos($query, ' '));
			} 
	}


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
$ls->parseEntries();




?>