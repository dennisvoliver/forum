
<?php // signup request
// This program creates a new account in our forum.
// It waits for user input.
// After both form has been submitted, it sends
// the information to another session of the program and ends itself.
// The new session will determine that the form has been
// submitted by the user to the calling program.
// It then checks whether both username and password are not empty.
// If either of the fields are empty, it prompts the user for more input.
// Otherwise , it attempts to create a new entry in the forum.members database.
// This database has only two columns, named username and password.
// If the supplied username already exists, the user is prompted.
// Otherwise, an entry is created and the user is prompted also.
// Afterwards, the programs goes back to its initial state.



require_once "mysql_login.php"; // setup sql connection
require_once "sanitize.php";	// string input sanitizer
require_once "functions.php";

echo "<html><body>";
$db = "forum";
$tb = 'members';
$link = mysqli_connect($hn, $un, $pw, $db);
$form = 0;
if (mysqli_connect_errno()) {
	mysqli_fatal_error('could not connect' . mysqli_connect_error());
	goto tail;
}


// if form is submitted
if (isset($_POST['username'])) {
	$username = sanitize_mysql($link, $_POST['username']);
	$password = sanitize_mysql($link, $_POST['password']);
	$result = mysqli_query($link, "SELECT * FROM $db.$tb WHERE username = '$username'");

	if (!$result) {
		mysql_fatal_error(mysql_connect_error($link));
		goto tail2;
	}
	$row = mysqli_fetch_array($result, MYSQLI_NUM);

	// if username exists already
	if ($row[0] != '') {
			echo "username $row[0] exists already";
			echo "<a href='login.php' >login instead</a>";

	// submit enter account
	} else {
		$password = $salt1 . $password . $salt2;
		$password = hash('ripemd128', "$password");
		$stmt = mysqli_prepare($link, 
		"INSERT INTO $db.$tb(username, password) VALUES(?,?)");
		mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
		mysqli_stmt_execute($stmt);
		$form = 1;
	}
}

// output form
if ($form == 0) {
echo <<<_END
<form action="signup.php" method="post">
username:
<input type="text" size="20" maxlength="30" name="username" required='required'>
password:
<input type="password" size="20" maxlength="30" name="password" required='required'>
<input type="submit">
</form>
_END;

} else {
echo <<<_END
account $username has been added;
<form action='thread.php' method='post'>
<input type='hidden' name='username' value='$username'>
<input type='submit' value='view threads'>
</form>
_END;

}


// cleanup
tail2:
mysqli_close($link); 
tail:
echo "</body></html>";


?>
