<?php
namespace gordonjmarc\logscraper\test;
use gordonjmarc\logscraper\LogScraper;

class LogScraperTest extends \PHPUnit_Framework_TestCase
{

   public function testLogParser()
    {
    	$file = "samples/slow_queries.log";

    	$ls = new LogScraper($file);
		$lr = $ls->parseEntries();

		var_dump($lr);

    	$this->assertEquals(true, true);
	}
}
?>