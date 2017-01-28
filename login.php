<?php // login

require_once "mysql_login.php"; // setup sql connection
require_once "Sanitize.php";	// string input sanitizer
require_once "functions.php"; // random functions

echo "<html><body>"; // head
$db = "forum";
$tb = 'members';
$conn = connect_mysql($hn, $un, $pw, $db);
$showform = true;

// if form is submitted
if (isset($_POST['username'])) {
	$username = sanitize_mysql($conn, $_POST['username']);
	$password = sanitize_mysql($conn, $_POST['password']);
	$password = $salt1 . $password . $salt2;
	$password = hash('ripemd128', $password);
	$query = "SELECT * FROM $tb WHERE username='$username' AND password='$password'";
	echo "$query";
	$result = get_query($conn,$query);

	// if username exists
	if ($result) {
		$result->data_seek(0);
		$row = $result->fetch_array($result, MYSQLI_NUM);
		$result->close();
		if ($row[0] == '') {
			if (!isset($_POST['changepass']))
				echo "incorrect username or password";
			$showform = true;
		} else
			$showform = false;

	} else {
		mysqli_fatal_error('result wrong');
	}
}


// password change
if (isset($_POST['changepass'])) {
	$password = sanitize_mysql($conn, $_POST['changepass']);
	$password = $salt1 . $password . $salt2;
	$password = hash('ripemd128', $password);
	$query= "UPDATE $tb SET password='$password' WHERE username='$username'"; 
	$result = $conn->query($query);
	if (!$result)
		mysqli_fatal_error('could not update password');
	else {
		$showform = false;
	}

}


// request username and password
if ($showform) {
echo <<<_END
<form action="login.php" method="post">
username:
<input type="text" size="20" maxlength="30" name="username" required='required'>
password:
<input type="password" size="20" maxlength="30" name="password" required='required'>
<input type="submit">
</form>

_END;

} else {
// goto forum and pass the $username value
// or change the password
echo <<<_END
<form action='forum.php' method='post'>
<input type='hidden' name='username' value='$username'>
You are now logged in.
<input type='submit' value='view forum'>
</form>

<form action='login.php' method='post'>
Change password?
<input type='text' name='changepass'>
<input type='hidden' name='username' value='$username'>
<input type='submit' value='change password'>
</form>
_END;
}

// cleanup
mysqli_close($conn);

echo "</body></html>"; // tail

?>
