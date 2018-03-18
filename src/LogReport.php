<?php

namespace gordonjmarc\logscraper;

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

?>