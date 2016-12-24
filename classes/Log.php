<?php

/**
* Logging class
*/
class Log{
	private $debug = false;
	private $data = "";
	public function setLog($value, $line){
		$this->data .= sprintf("[+] At Line %d, %s\n", $line ,$value);
	}
	public function getLog()
	{
		if ($this->isDebug()) {
			echo $this->data;
		}else{
			echo "--";
		}
	}
	function __construct($b=true){
		$this->debug = $b;
	}
	public function setDebug()
	{
		$this->debug = true;
	}
	public function isDebug(){
		return $this->debug;
	}
}