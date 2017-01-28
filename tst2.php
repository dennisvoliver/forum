<?php
// when a form is submitted the $_POST array is populated even though some fields are left blank
// the isset() function merely tests whether an array element exists at all, it doesn't
// care about its contents
require_once "mysql_login.php"; // setup sql connection
require_once "sanitize.php";	// string input sanitizer
$db = "forum";
$tb = 'members';

$out_uname = $out_pword = 'nothing';
if ($_POST['username'] && $_POST['username']) {
	$out_uname =  $_POST['username'];
	$out_pword =  $_POST['password'];
}

echo <<<_END
<html><body>
<p>
last username $out_uname
last password $out_pword
</p>
<form action="tst2.php" method="post">
username
<input type="text" name="username">
password
<input type="password" name="password">
<input type="submit">

</form>
</body></html>
_END;

?>
