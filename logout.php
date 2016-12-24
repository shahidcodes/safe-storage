<?php 
require_once 'core/init.php';
$u = new User();
$u->logout();
Redirect::to('index.php');