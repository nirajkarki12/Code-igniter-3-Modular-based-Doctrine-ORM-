<?php

function dateMysql($date)
{
	//return mdate('%M %d, %Y - %h:%i %a',mysql_to_unix($date));
	return mdate('%M %d, %Y',mysql_to_unix($date));
}

function dateMysqlWithTime($date)
{
	return mdate('%M %d, %Y - %h:%i %a',mysql_to_unix($date));
}

function isValidDate($date)
{
	if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts))
		if(checkdate($parts[2],$parts[3],$parts[1])) return true;
	return false;
}

function getLocalTime($UTC_Time, $agent = NULL)
{
	$user = \Current_User::user();

	$agent = ( $agent )?:  $user->getAgent();

	$gmtoffset = ($agent)? $agent->getTimezone()->getGmtOffset() : timezone_offset_get(date_default_timezone_get(), new \DateTime());

	$sign = ($gmtoffset < 0)? '-' : '+';

	$GMT_offset = $gmtoffset * 60 ;

	$offset_string = $sign.$GMT_offset.' minutes';

	$value = $UTC_Time->modify($offset_string)->format('Y-m-d H:i:s');

	return  new \DateTime($value);
}

?>