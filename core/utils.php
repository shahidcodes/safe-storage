<?php

function escape($string)

{

	return htmlentities($string, ENT_QUOTES, 'UTF-8');

}

function getUserName(){
	$user = new User();
	return $user->isLogged() ? $user->data()->name : " Guest!";
}

function dd($value){
	var_dump($value);
	die();
}


function getHeader(){
	include "includes/header.php";
}

function getFooter(){
	include "includes/footer.php";
}