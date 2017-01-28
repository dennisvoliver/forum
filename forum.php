<?php // create new thread, view existing ones


require_once "mysql_login.php"; // setup sql connection
require_once "Sanitize.php";

echo "<html><body>";
$db = "forum";
$tb = 'threads';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error)
	die($conn->connect_error);


// set author
if (!isset($_POST['username'])) {
	$username = 'anonymous';
} else
	$username = sanitize_mysql($conn, $_POST['username']);

// create new thread
if (isset($_POST['title'])) {
	$title = sanitize_mysql($conn, $_POST['title']);
	if ($title == '') {
		echo "blank title not allowed";
		break;
	}
	$result = $conn->query("SELECT * FROM threads where title='$title'");
	if (!$result) die($conn->error);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	if ($row['title'] != '')
		echo "thread_" . $row['id'] . "exists already";
	else {
		$result = $conn->query("INSERT INTO threads(title,author) VALUES('$title','$username')");
		if (!$result) die($conn->error);
		$result = $conn->query("SELECT id FROM threads WHERE title='$title'");
		if (!$result) die($conn->error);
		$row = $result->fetch_array(MYSQLI_NUM);
		$conn->query("CREATE TABLE thread_$row[0] (id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY, author VARCHAR(30), comment VARCHAR(128)) ENGINE MyISAM;");
		if (!$result) die($conn->error);
		
	}
	$result->close();
}



//display threads
$result = $conn->query("SELECT * FROM threads");
if (!$result) die($conn->error);
$row = $result->fetch_array(MYSQLI_ASSOC);
$rows = $result->num_rows;
for ($i = 0; $i < $rows; $i++) {
	$result->data_seek($i);
	$row = $result->fetch_array(MYSQLI_NUM);
	$thread = $row[0];
echo <<<_END
<form action='thread.php' method='post'>
<input type='hidden' name='username' value='$username'>
<input type='hidden' name='thread' value='$thread'>
<input type='submit' value='view thread'>
$thread author: $row[2] title: $row[1] 
<br>
</form>
_END;

}


$result->close();


// output form
echo <<<_END
<form action="forum.php" method="post">
title:
<input type="text" size="20" maxlength="30" name="title">
<input type="submit" value='Create New Thread'>
</form>
_END;




// cleanup
$conn->close();
echo "</body></html>";


?>
