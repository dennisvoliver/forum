<?php
function sanitize_string($var)
{
	$var = stripslashes($var);
	$var = strip_tags($var);
	$var = htmlentities($var);
	return $var;
}

function sanitize_mysql($connection, $var)
{
	$var = $connection->real_escape_string($var);
	$var = sanitize_string($var);
	return $var;
}
?>
