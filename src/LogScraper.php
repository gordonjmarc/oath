<?php

namespace gordonjmarc\logscraper;

/**
 * Parses contents of whole log, creates log report
 */
class LogScraper
{
	public $file;
	public $contents;

	/* regex pattern used for parsing input into individual log entries */

	public $entryPattern=  "/# User@Host: /"; 


	/* regex patterns used to parse individual fields/headers in log entries */

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
			throw new \FileNotFoundException("File not found");
		}

		$this->file = new \SplFileInfo($file);
		$contents = $this->file->openFile("r");
		$this->contents = $contents->fread($this->file->getSize());

		$this->logReport = new LogReport();
	}

	public function parseEntries() 
	{

		$rawEntries = preg_split($this->entryPattern, $this->contents, -1, PREG_SPLIT_NO_EMPTY);
		
		if(empty($rawEntries)) 
		{
			//TODO: throw empty report exception
		}


		foreach($rawEntries as $rawEntry) 
		{
			$le = new LogEntry();

	 		if(preg_match($this->queryTimePattern, $rawEntry, $matches) ) 
			{
				$le->queryTime($matches[1]);
			}
			
			if(preg_match($this->lockTimePattern, $rawEntry, $matches) ) 
			{
				$le->lockTime($matches[1]);
			}

			if(preg_match($this->rowsSentPattern, $rawEntry, $matches) ) 
			{
				$le->rowsSent($matches[1]);
			}

			if(preg_match($this->bytesSentPattern, $rawEntry, $matches) ) 
			{
				$le->bytesSent($matches[1]);
			}

			if(preg_match($this->rowsExaminedPattern, $rawEntry, $matches) ) 
			{
				$le->rowsExamined($matches[1]);
			}

			if(preg_match($this->rowsEffectedPattern, $rawEntry, $matches) ) 
			{
				$le->rowsEffected($matches[1]);
			}

			if(preg_match($this->timeStampPattern, $rawEntry, $matches) ) 
			{
				$le->timeStamp($matches[1]);
			}

			if(stripos($rawEntry, ';') > 0) //query exists between semi colons
			{
				$le->query( trim ( substr($rawEntry, stripos($rawEntry, ';')+1 ) ) );
				
			}

			$this->logReport->addEntry($le);
		}

		return $this->logReport;
	}

}




?>