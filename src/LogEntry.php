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
	private $_rowsAffected;
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

    public function rowsAffected($init = null)
    {
    	if(!is_null($init))
		{
			if(!is_numeric($init)) 
			{
				throw new \InvalidArgumentException("invalid rowsAffected");		
			}

			$this->_rowsAffected = (int) $init;
		}

		return $this->_rowsAffected;
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



?>