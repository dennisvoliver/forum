<?php // random functions

function get_query($conn, $query)
{
	$result = $conn->query($query);
	if (!$result) mysql_fatal_error($conn->error);
	return $result;
}

function connect_mysql($hn, $un, $pw, $db)
{
	$conn = new mysqli($hn, $un, $pw, $db);
	if ($conn->connect_error) die($conn->connect_error);
	return $conn;
}

function getglob($method, $index)
{
	if ($method == 'GET') {
		if (isset($_GET[$index])) {
			return $_GET[$index];
		} else die("$index not in \$_GET");
	} else {
		if (isset($_POST[$index]))
			return $_POST[$index];
		else die("$index not in \$_POST");
	}
}

function breakhere()
{
	echo "pointbreak";
	echo "</body></html>";
	die();
}

function mysqli_error_handler($msg, $err)
{
	echo "<p>$msg: $err</p>";
	die($err);
}

function mysql_fatal_error($msg)
{
$msg2 = mysql_error();
echo <<<_END
We are sorry, but it was not possible to complete
the requested task. The error message we got was:
<p>$msg: $msg2</p>
Please click the back button on your browser
and try again. If you are still having problems,
please <a href="mailto:admin@server.com">email
our administrator</a>. Thank you.
</body></html>
_END;

}



?>
