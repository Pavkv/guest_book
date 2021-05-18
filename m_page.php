<?php
$db = pg_connect("host=localhost dbname=guest_book user=pavkv password=8421537690") or die("Can't connect to database".pg_last_error());
if (isset($_POST['login'])){
    $name = $_POST['usern'];
    $pasw = $_POST['passwd'];
    $login_successful = false;
    $admin = false;
    $pid = 0;
    $query = "SELECT max(user_id) FROM users";
    $result = pg_query($db, $query) or die("Can't find data: " . pg_last_error());
    if ($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
        foreach ($line as $value) $pid = $value;
    }
    $login_successful = false;
    $admin = false;
    $pid = 0;
    $query = "SELECT max(user_id) FROM users";
    $result = pg_query($db, $query) or die("Can't find data: " . pg_last_error());
    if ($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
        foreach ($line as $value) $pid = $value;
    }
    while ($pid > 0){
        $query2 = "SELECT * FROM users WHERE user_id='".$pid."'";
        $result2 = pg_query($db, $query2) or die("Can't find data: " . pg_last_error());
        $i = 0;
        if ($line = pg_fetch_array($result2, null, PGSQL_ASSOC)){
            foreach ($line as $value){
                if ($i == 0) {$usr = $value; $i++; continue;}
                if ($i == 1) {$pwd = $value; $i++; continue;}
                if ($i == 2 && $value == 1) {$admin = true; $i++;}
                else $i++;
                if ($i == 3) {
                    if($name == $usr && $pasw == $pwd && $admin) $login_successful = true;
                    if ($name == $usr && $pasw == $pwd && !$admin) $login_successful = true;
                    $i = 0;
                }
            }
        }
        if ($login_successful) break;
        else $pid--;
    }
    if(!$login_successful){
        echo 'Check the correctness of entered data';
    }
    elseif($admin) header('Location: a_page.php');
    else {
        session_start();
        $_SESSION['nams'] = $name;
        header('Location: b_page.php');
    }

}
if (isset($_POST['sign'])) {
    header('Location: sign_up.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>GUEST BOOK</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<form method="post" target="_self" id="gb-form">
    <label style="text-align: center">WELCOME TO GUESTBOOK, IF YOU ALREADY HAVE AN ACCOUNT LOG IN OR SIGN UP</label>
    <label for="name">Username:</label>
    <input type="text" name="usern" required/>
    <label for="passwd">Password:</label>
    <input type="password" name="passwd" required>
    <input type="submit" value="Log in" name="login"/>
</form>
<form method="post" target="_self" id="gb-form">
    <input type="submit" value="Sign up" name="sign"/>
</form>
</body>
</html>


