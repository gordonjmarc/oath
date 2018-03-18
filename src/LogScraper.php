<?php
/**
 * Parses contents of whole log
 */



class LogScraper
{

	private $file;
	private $contents;

    public function __construct($file)
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException("File not found");
        }

		$this->file = new \SplFileInfo($file);
        $contents = $this->file->openFile("r");
        $this->contents = $contents->fread($this->file->getSize());
    }
    
	public function parseEntries() 
	{
        $pattern=  "/# User@Host: /"; 

 		//$hostPattern = "/ .*@ (.*) Id:/";
        $queryTimePattern = "/Query_time: (([0-9]|\.)*)/";
 		$lockTimePattern = "/Query_time: (([0-9]|\.)*)/";
 		$rowsSentPattern = "/Rows_sent: (([0-9])*)/";
        $bytesSentPattern = "/Bytes_sent: (([0-9])*)/";
        $rowsExaminedPattern = "/Rows_examined: (([0-9])*)/";
        $rowsEffectedPattern = "/Rows_affected: (([0-9])*)/";
        $timeStampPattern = "/SET timestamp=(([0-9])*)/";

        $entries = preg_split($pattern, $this->contents, -1, PREG_SPLIT_NO_EMPTY);

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

        	
	 		if(preg_match($queryTimePattern, $entry, $matches) ) 
			{
				$queryTime= $matches[1];
			}
			
			if(preg_match($lockTimePattern, $entry, $matches) ) 
			{
				$lockTime = $matches[1];
			}

			if(preg_match($rowsSentPattern, $entry, $matches) ) 
			{
				$rowsSent = $matches[1];
			}

            if(preg_match($bytesSentPattern, $entry, $matches) ) 
            {
                $bytesSent = $matches[1];
            }

            if(preg_match($rowsExaminedPattern, $entry, $matches) ) 
            {
                $rowsExamined = $matches[1];
            }

            if(preg_match($rowsEffectedPattern, $entry, $matches) ) 
            {
                $rowsEffected = $matches[1];
            }

            if(preg_match($timeStampPattern, $entry, $matches) ) 
            {
                $timeStamp = $matches[1];
            }

            if(stripos($entry, ';') > 0) //query exists between semi colons
            {
                $query = substr($entry, stripos($entry, ';'));
                $queryType = substr($query, 0 , stripos($query, ' '));

            } 

     

            //echo "\n";
            //var_dump($timeStamp);
           // var_dump($query);
          //  var_dump($queryType);

          //  die();
     }


        
       // var_dump( count( $entries) );
       
       // var_dump($entries[0]);
        //echo "\n";
       
        //var_dump($entries[1]);
       // var_dump($entries[count($entries) -1 ]    );


	}    	


}

$file = $argv[1];

if(empty($file)) 
{
	throw new InvalidArgumentException("arg 1 must be a file");
}

$ls = new LogScraper($file);
$ls->parseEntries();





?>