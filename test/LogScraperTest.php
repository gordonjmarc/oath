<?php
namespace gordonjmarc\logscraper\test;
use gordonjmarc\logscraper\LogScraper;
use gordonjmarc\logscraper\LogEntry;
use gordonjmarc\logscraper\LogReport;



class LogScraperTest extends \PHPUnit_Framework_TestCase
{

	public function testLogParserSelect()
	{
		$file = "samples/slow_queries.log";

		$ls = new LogScraper($file);
		$lr = $ls->parseEntries();

		$this->assertEquals($lr->totalEntries(), 2081);
	}

	public function testLogParser()
	{
		$file = "samples/slow_queries.log";

		$ls = new LogScraper($file);
		$lr = $ls->parseEntries();

		$this->assertEquals($lr->totalSelectEntries(), 1890);
	}

 	/**
     * @expectedException InvalidArgumentException
     */
	public function testLogParserWithErrors()
	{
		$file = "samples/slow_queries_with_errors.log";

		$ls = new LogScraper($file);
		$lr = $ls->parseEntries();

	}

	public function testLogParserSmall()
	{
		$file = "samples/slow_queries_small.log";

		$ls = new LogScraper($file);
		$lr = $ls->parseEntries();

		$this->assertEquals($lr->totalSelectEntries(), 3);
	}
}
?>