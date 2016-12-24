<?php

/**
* Getting all config from $GLOBALS['config'] in init.php
*  @author : Shahid Kamal (@sh4hidkh4n)
* @link : https://github.com/shahidkh4n/safe-storage
*/

class Config

{

	

	public static function get($path = null)

	{

		if($path){		

			$config = $GLOBALS['config'];

			$path = explode('/', $path);

			foreach($path as $bit)

			{

				if (isset($config[$bit])) {

					$config = $config[$bit];



				}

			}

			return $config;

		}

		return false;

	}

}