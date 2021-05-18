<?php
if(isset($_POST['sign'])){
    $db = pg_connect("host=localhost dbname=guest_book user=pavkv password=8421537690") or die("Can't connect to database".pg_last_error());
    $name = $_POST['usern'];
    $pasw = $_POST['passwd'];
    $k = 0;
    $query = "SELECT max(user_id) FROM users";
    $result = pg_query($db, $query) or die("Can't find data: " . pg_last_error());
    if ($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
        foreach ($line as $value) $k = $value + 1;
    }
    $namecheck = true;
    $query3 = "SELECT username FROM users";
    $result3 = pg_query($db, $query3) or die("Can't find data: " . pg_last_error());
    if ($line = pg_fetch_array($result3, null, PGSQL_ASSOC)){
        foreach ($line as $value) if ($name == $value) $namecheck = false;
    }
    if($namecheck){
        $query1 = "INSERT INTO users (username, password, user_id) VALUES ('".$name."', '".$pasw."', '".$k."')";
        $result1 = pg_query($db, $query1) or die("Can't add new data: " . pg_last_error());
        session_start();
        $_SESSION['nams'] = $name;
        header('Location: b_page.php');
    }
    else echo 'This username already exists';
}?>
<!DOCTYPE html>
<html>
<head>
    <title>GUEST BOOK</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<form method="post" target="_self" id="gb-form">
    <label for="name">Username:</label>
    <input type="text" name="usern" required/>
    <label for="passwd">Password:</label>
    <input type="password" name="passwd" required>
    <input type="submit" value="Sign up" name="sign"/>
</form>
</body>
</html>
