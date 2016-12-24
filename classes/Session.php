<?php



 class Session{

 	public static function put($name, $value)

 	{

 		return $_SESSION[$name] = $value;

 	}



 	public static function exists($name)

 	{

 		return (isset($_SESSION[$name])) ? true : false;

 	}

 	public static function get($name)
 	{

 		return $_SESSION[$name];

 	}

 	public static function delete($name)

 	{

 		if (self::exists($name)) {

 			unset($_SESSION[$name]);

 		}

 	}



 	public static function flash($name, $string = null)
 	{

 		if (self::exists($name) && $string == null) { // when to flash msg only with key
 			$session = self::get($name);
			self::delete($name);
 			return $session;
 		}else if($string){
 			self::put($name, $string);
 		}
 	}

 }