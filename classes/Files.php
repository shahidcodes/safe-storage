<?php

/**
* File management class
*/
class Files
{
	private $_db;
	function __construct($file_id=null)
	{
		$this->_db = DB::getInstance();
	}

	public function getAllFiles()
	{
		$files = $this->_db->get("file_meta", [1, "=", 1])->results();
		return $files;
	}
}