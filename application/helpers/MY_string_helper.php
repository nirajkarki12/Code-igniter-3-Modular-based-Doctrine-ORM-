<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function getSentence($string,$numwords = 50)
{
	$words = explode(" ",$string);
	
	$part = array_slice($words,0,$numwords);
	
	return implode(" ",$part);
}

