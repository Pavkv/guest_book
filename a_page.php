<?php
require "core.php";
$_GB = new GuestBook();
$db = pg_connect("host=localhost dbname=guest_book user=pavkv password=8421537690") or die("Can't connect to database".pg_last_error());
$uid = 0;
$query = "SELECT max(user_id) FROM users";
$result = pg_query($db, $query) or die("Can't find data: " . pg_last_error());
if ($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
    foreach ($line as $value) $uid = $value;
}
if (isset($_GET['delete'])) {
    $i = $_GET['delete'];
    $query1 = "DELETE FROM users WHERE user_id='" . $i . "'";
    $result1 = pg_query($db, $query1) or die("Can't find data: " . pg_last_error());
    $_SESSION['f'] = $i;
    header('Location: p_delete.php');
}
if(isset($_GET['edited'])){
    $username = $_GET['username'];
    $namecheck = true;
    $query2 = "SELECT username FROM users";
    $result2 = pg_query($db, $query2) or die("Can't find data: " . pg_last_error());
    if ($line = pg_fetch_array($result2, null, PGSQL_ASSOC)){
        foreach ($line as $value) if ($username == $value) $namecheck = false;
    }
    if(!$namecheck){
        $username.='(1)';
    }
    $password = $_GET['password'];
    $data = $_GET['data'];
    $i = $_GET['username2'];
    $query3 = "UPDATE users SET username = '" . $username . "', password = '" . $password . "', data = '" . $data . "' WHERE user_id ='" . $i . "'";
    $result3 = pg_query($db, $query3) or die("Can't find data: " . pg_last_error());
}
if(isset($_POST['go'])){
    $uid++;
    $user = $_POST['usr'];
    $namecheck = true;
    $query4 = "SELECT username FROM users";
    $result4 = pg_query($db, $query4) or die("Can't find data: " . pg_last_error());
    if ($line = pg_fetch_array($result4, null, PGSQL_ASSOC)){
        foreach ($line as $value) if ($user == $value) $namecheck = false;
    }
    if(!$namecheck){
        $user.='(1)';
    }
    $pasw = $_POST['psw'];
    $data = $_POST['dta'];
    if ($_GB->save2($user, $pasw, $uid, $data)) {
        echo "<div>New User Saved</div>";
    } else {
        echo "<div>$_GB->error</div>";
    }
    unset($_POST['go']);
}
$s = 1;
$k = 1;
?>
<!DOCTYPE html>
<html>
<head>
    <title>GUEST BOOK</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<form style="text-align: center">
    <table>
        <tr>
            <th>User</th>
            <th>Password</th>
            <th>Data</th>
            <th>Actions</th>
        </tr>
        <?php while ($uid >= $s){
        $entries = $_GB->get2($s);?>
        <?php if (count($entries)>0) { foreach ($entries as $e) { ?>
        <tr>
            <td><?php echo $e['username'];?></td>
            <td><?php echo $e['password'];?></td>
            <td><?php echo $e['data'];?></td>
            <td><button type="submit" class="deletebutton" style="position: relative; left: 45px;" name="delete" value="<?php echo $k?>"></button>
                <button type="submit" class="editbutton" style="position: relative; left: -5px;" name="edit" value="<?php echo $k?>"></button>
            </td>
        </tr>
        <?php }}?>
            <?php $k++; $s++;}?>
    </table>
</form>
<form method="get" target="_self" id="gb-form" style="<?php if(!isset($_GET['edit']) || isset($_GET['canceled'])) echo 'display:none'?>">
    <label for="username">Username:</label>
    <input type="hidden" name="username2" value="<?php echo $_GET['edit']; ?>"/><input type="text" name="username" value="<?php  $entries = $_GB->get2($_GET['edit']); if (count($entries)>0)
    { foreach ($entries as $e) { echo $e['username']; }}?>">
    <label for="password">Password:</label>
    <input type="text" name="password" value="<?php  $entries = $_GB->get2($_GET['edit']); if (count($entries)>0)
    { foreach ($entries as $e) { echo $e['password']; }}?>">
    <label for="password">Password:</label>
    <input type="text" name="data" value="<?php  $entries = $_GB->get2($_GET['edit']); if (count($entries)>0)
    { foreach ($entries as $e) { echo $e['data']; }}?>">
    <input type="submit" value="Save" name="edited">
    <input type="submit" value="Cancel" name="canceled">
</form>
<form method="post" target="_self" id="gb-form">
    <label for="username">Username:</label>
    <input name="usr" required>
    <label for="password">Password:</label>
    <input name="psw" required>
    <label for="data">Data:</label>
    <textarea name="dta"></textarea>
    <input type="submit" value="Add new user" name="go"/>
</form>
</body>
</html>


