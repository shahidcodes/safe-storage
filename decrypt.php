<?php
require_once 'core/init.php';


$user = new User();
if ($user->isLogged() && Input::exists("get")) {
	// header('Content-type: image/jpeg'); //set header
	$password = Input::get("password");
	if ($password == "") {
		$password = Session::get("password");
	}
	$checksum = Input::get("file");
	$decrypt  = new Encryption();
	$decrypt->decrypt($password, $checksum);
}