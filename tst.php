<?php
// RESULT IS NOT NULL IF MYSQLI CANNOT FIND YOUR QUERY
require_once "mysql_login.php"; // setup sql connection
require_once "sanitize.php";	// string input sanitizer
$db = "forum";
$tb = 'members';
$link = mysqli_connect($hn, $un, $pw, $db);
//$result = mysqli_query($link, "SELECT * FROM members WHERE username = denniz200");
$username = 'dennisz100';
$result = mysqli_query($link, "SELECT * FROM $db.$tb WHERE username = '$username'");

if (!$result) {
	echo "result null";
	return;
} else {
	mysqli_data_seek($result, 0);
	$row = mysqli_fetch_array($result);
	if ($row[0] == '')
		echo "string is empty";
	else
		echo $row[0];
}
?>
