<?php

require('src/LogEntry.php');
require('src/LogReport.php');
require('src/LogScraper.php');

$file = null;
$startTime = null;
$endTime = null;


if(!empty($argv[1]))
{
	$file = $argv[1];
}

if(!empty($argv[2]))
{
	$timeInput1 = $argv[2];

	if(is_numeric($timeInput1) && (int) $timeInput1 == $timeInput1 ) 
	{
		$startTime = $timeInput1;
	}
	else
	{
		throw new \InvalidArgumentException("arg 2 must be a start time (as epoch)");
	}

}

if(!empty($argv[3]))
{
	$timeInput2 = $argv[3];

	if(is_numeric($timeInput2) && (int) $timeInput2 == $timeInput2 ) 
	{
		$endTime = $timeInput2;	
	}
	else
	{
		throw new \InvalidArgumentException("arg 3 must be an end time (as epoch)");
	}

}

if(empty($file)) 
{
	throw new \InvalidArgumentException("arg 1 must be a file");
}


$ls = new \gordonjmarc\logscraper\LogScraper($file);
$lr = $ls->parseEntries();

$lr->printSummaryReport(null, $startTime, $endTime);

?>