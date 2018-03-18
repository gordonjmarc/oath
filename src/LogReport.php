<?php

namespace gordonjmarc\logscraper;

/**
 * Collection of LogEntries, and Reporting Methods
 */
class LogReport
{
	private $entries = array();

	public function addEntry($le) 
	{
		$this->entries[] = $le;
	}

	public function printSummaryReport($queryType = null, $startTime = null, $endTime = null) 
	{
		$queryTimeTotal = 0;
		$lockTimeTotal = 0;
		$rowsSentTotal = 0;
		$bytesSentTotal = 0;
		$rowsExaminedTotal = 0;
		$rowsEffectedTotal = 0;

		foreach($this->entries as $entry) 
		{
			if(!is_null($queryType) && $queryType != $entry->queryType() ) 
			{
				continue;
			}

			if(!is_null($startTime) && !is_null($endTime) && 
				($entry->timeStamp() < $startTime ||
				 $entry->timeStamp() > $endTime) )
			{
				continue;
			} 
			elseif(!is_null($startTime) && $entry->timeStamp() < $startTime)
			{
				continue;
			}
			elseif(!is_null($endTime) && $entry->timeStamp() > $endTime)
			{
				continue;
			} 

			$queryTimeTotal += $entry->queryTime();
			$lockTimeTotal += $entry->lockTime();
			$rowsSentTotal += $entry->rowsSent();
			$bytesSentTotal += $entry->bytesSent();
			$rowsExaminedTotal += $entry->rowsExamined();
			$rowsEffectedTotal += $entry->rowsEffected();

		}

		echo "\nslow query summary report: \n\n";
		
		if(!is_null($queryType)) 
		{
			echo "query type filter: {$queryType}\n";
		}

		if(!is_null($startTime))
		{
			echo "using start time filter: {$startTime}\n";
		}

		if(!is_null($endTime))
		{
			echo "using end time filter: {$endTime}\n";
		}

		echo "query time total: {$queryTimeTotal}\n";
		echo "lock time total: {$lockTimeTotal}\n";
		echo "rows sent total: {$rowsSentTotal}\n";
		echo "bytes sent total: {$bytesSentTotal}\n";
		echo "rows examined total: {$rowsExaminedTotal}\n";
		echo "rows effected total: {$rowsEffectedTotal}\n\n";
	}

}

?>