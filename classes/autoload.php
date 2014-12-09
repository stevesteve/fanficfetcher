<?php
function __autoload($class_name) {
    if(file_exists(__DIR__ . '/class.' . $class_name . '.php')) {
        require_once(__DIR__ . '/class.' . $class_name . '.php');    
    } else {
		if(file_exists(__DIR__. '/vendor/PHPePub-master/' . $class_name . '.php')){
			require_once(__DIR__. '/vendor/PHPePub-master/' . $class_name . '.php');    
		}else{
			throw new Exception("Unable to load ".__DIR__."\\class.".$class_name.".php");	
		}
        
    }
}
?>