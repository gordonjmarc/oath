<?php

namespace gordonjmarc\logscraper;
/**
 * An enty with data captured from LogScraper
 */
class LogEntry
{
	private $_queryTime;
	private $_lockTime;
	private $_rowsSent;
	private $_bytesSent;
	private $_rowsExamined;
	private $_rowsEffected;
	private $_timeStamp;
	private $_query;
	private $_queryType;


	public function queryTime($init = null)
    {
    	if(!is_null($init))
		{
			if(!is_numeric($init)) 
			{
				throw new \InvalidArgumentException("invalid queryTime");
			}

    		$this->_queryTime = (float) $init;
    	}

    	return $this->_queryTime;
    }

    public function lockTime($init = null)
    {
		if(!is_null($init))
		{
			if(!is_numeric($init)) 
			{
				throw new \InvalidArgumentException("invalid lockTime");
			}

			$this->_lockTime = (float) $init;
		}

		return $this->_lockTime;
    }

    public function rowsSent($init = null)
    {
    	if(!is_null($init))
		{
			if(!is_numeric($init)) 
			{
				throw new \InvalidArgumentException("invalid rowsSent");	
			}

			$this->_rowsSent = (int) $init;
		}

		return $this->_rowsSent;
    }

    public function bytesSent($init = null)
    {
    	if(!is_null($init))
		{
			if(!is_numeric($init)) 
			{
				throw new \InvalidArgumentException("invalid bytesSent");	
			}

			$this->_bytesSent = (int) $init;
		}

		return $this->_bytesSent;
    }

    public function rowsExamined($init = null)
    {
    	if(!is_null($init))
		{
			if(!is_numeric($init)) 
			{
				throw new \InvalidArgumentException("invalid rowsExamined");
			}

			$this->_rowsExamined = (int) $init;
		}

		return $this->_rowsExamined;
    }

     public function rowsEffected($init = null)
    {
    	if(!is_null($init))
		{
			if(!is_numeric($init)) 
			{
				throw new \InvalidArgumentException("invalid rowsEffected");		
			}

			$this->_rowsEffected = (int) $init;
		}

		return $this->_rowsEffected;
    }

    public function timeStamp($init = null)
    {
    	if(!is_null($init))
		{
			if(!is_numeric($init)) 
			{
				throw new \InvalidArgumentException("invalid timeStamp");	
			}

			$this->_timeStamp = (int) $init;	
		}

		return $this->_timeStamp;
    }

    public function query($init = null)	
    {
    	if(!is_null($init))
		{
			if(empty($init))
			{
				throw new \InvalidArgumentException("invalid query");
			}

			

			$queryType =  substr($init, 0 , stripos($init, ' ') ) ;

			if(empty($queryType) || ! ($queryType == "SELECT" || $queryType == "UPDATE"  ||
										$queryType == "INSERT"  || $queryType == "DELETE" ) )
			{
				throw new \ InvalidArgumentException("invalid query type");
			}

			$this->_query = $init;	
			$this->_queryType = $queryType;

		}

		return $this->_query;
    }

    public function queryType($init = null)	
    {
		return $this->_queryType;
    }

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