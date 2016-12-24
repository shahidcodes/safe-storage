<?php
/**
 * PHP Class for generating captcha hashes and validating them
 * @author : Shahid Kamal (@sh4hidkh4n)
 * @link : https://github.com/shahidkh4n/safe-storage
 */
class Captcha {
	private static $_capError = false;
	public static function get($name)
	{
		return Session::get($name);
	}

	public static function check($value)
	{
		if (Session::exists('secure')) {
			if ($value == Session::get('secure')) {
				return true;
			}else{
				self::$_capError = true;
			}
		}
		//return false;
	}

	public static function error()
	{
		return self::$_capError;
	}
}