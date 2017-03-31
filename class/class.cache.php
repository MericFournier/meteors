<?php

class cache
{
	public $dirname; // direction file
	public $duration; // time limit for the file

	public function __construct($dirname, $duration)
	{
		// get both variable
		$this->dirname = $dirname; 
		$this->duration = $duration;
	}

	public function write($filename, $content)
	{
		return file_put_contents($this->dirname.'/'.$filename, $content); // write in the dir file
	}

	public function read($filename)
	{
		$file = $this->dirname.'/'.$filename;
		if (!file_exists($file))
		{
			return false;  // if the file doesnt exist return false and start writing a new file
		}
		$life_time = (time() - filemtime($file)) / 60;
		if ($life_time > $this->duration)
		{
			return false; // same if the duration limit is exceeded
		}
		return file_get_contents($file);
	}
}