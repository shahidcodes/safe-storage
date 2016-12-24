<?php
class Redirect {
    //to redirect 
    public static function to($location){

    //redirect using some keyword like 404, 503/forbidden etc
    
    	switch ($location) {
    			case '404':
    				header('HTTP/1.0 404 Not Found');
    				include 'includes/errors/404.php';
    				exit();
    				break;
    		}

    //or redirect to some provided location

        if($location){
            header("Location: ".$location);
            exit();
        }
    }
} 
    