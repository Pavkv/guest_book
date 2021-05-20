<?php
session_start();
$db = pg_connect("host=localhost dbname=guest_book user=pavkv password=8421537690") or die("Can't connect to database".pg_last_error());
$query2 = "UPDATE users SET user_id = user_id - 1 WHERE user_id >'" . $_SESSION['f'] . "'";
$result2 = pg_query($db, $query2) or die("Can't find data: " . pg_last_error());
header('Location: a_page.php');