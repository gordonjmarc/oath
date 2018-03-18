<?php

require('src/LogEntry.php');
require('src/LogReport.php');
require('src/LogScraper.php');

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
	throw new \InvalidArgumentException("arg 1 must be a file");
}

//TODO: handle timestamp inputs

$ls = new \gordonjmarc\logscraper\LogScraper($file);
$lr = $ls->parseEntries();

var_dump($lr);

?>