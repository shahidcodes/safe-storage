<?php

class Hash {
	public static function make($string, $salt='')
	{
		return hash('sha256', $string.$salt);
	}
	public static function salt($len)
	{
		return mcrypt_create_iv($len);
	}
	public static function unique()
	{
		return self::make(uniqid());
	}
}