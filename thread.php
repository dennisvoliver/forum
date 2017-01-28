<?php // comments on a thread 

require_once "mysql_login.php";
require_once "Sanitize.php";
require_once "functions.php";


$thread = -1;
$username = 'anonymous';
// get some variables
$thread = sanitize_string(getglob('POST', 'thread'));
$username = sanitize_string(getglob('POST', 'username'));

echo <<<_END
<html>
<head>
<title>	thread_$thread </title>
</head>
<body>
_END;

// setup mysql connection
$conn = connect_mysql($hn, $un, $pw, $db);
$result = get_query($conn, "DESCRIBE thread_$thread;");
$thread = sanitize_mysql($conn, $thread);
$username = sanitize_mysql($conn, $username);


// insert new comment (if any)
if (isset($_POST['comment'])) {
	$comment = sanitize_mysql($conn, $_POST['comment']);
	$result = $conn->query("INSERT INTO thread_$thread(author,comment) VALUES('$username','$comment')");
	if (!$result) {
		echo "cannot insert";
		die();
	}
}


// display previous comments
$query = "SELECT * FROM thread_$thread";
$result = $conn->query($query);
if (!$result) die($conn->error);
$rows = $result->num_rows;
echo "<p>";
for ($i = 0; $i < $rows; $i++) {
	$result->data_seek($i);
	$row = $result->fetch_array(MYSQLI_NUM);
	echo"$row[1] : $row[2] <br>";

}
echo "</p>";

// display create comment form
$result->close();
$conn->close();

echo <<<_END
<form action='thread.php' method='post'>
<textarea name='comment' rows='20' cols='50' autofocus='on'>
</textarea>
<br>
<input type='hidden' name='username' value='$username'>
<input type='hidden' name='thread' value='$thread'>
<input type='submit' value='Comment'>
</form>
</body>
</html>
_END;


?>
