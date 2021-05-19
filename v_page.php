<?php
if(isset($_GET['go'])) header('Location: b_page.php');
session_start();
$nm = $_SESSION['nams'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>GUEST BOOK</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<?php
require "core.php";
$db = pg_connect("host=localhost dbname=guest_book user=pavkv password=8421537690") or die("Can't connect to database".pg_last_error());
$pid = 0;
$query = "SELECT max(post_id) FROM guestbook";
$result = pg_query($db, $query) or die("Can't find data: " . pg_last_error());
if ($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
    foreach ($line as $value) $pid = $value;
}
$_GB = new GuestBook();
$i = 1;
$k = 1;
while ($pid >= $i){
    $entries = $_GB->get($i);
    $dels = 'delete' . $k;?>
    <div id='gb-entries'>
        <?php if (count($entries)>0) { foreach ($entries as $e) { ?>
            <form>
                <div class="gb-row">
                    <?php if ($e['name'] == $nm) {
                        echo '<button type="submit" class="deletebutton" name="'.$dels.'"  onclick=""></button>' ?>
                        <button class="editbutton"></button>
                    <?php }?>
                    <div class="gb-datetime"><?=$e['datetime']?></div>
                    <div class="gb-name">
                        <span class="gb-name-a"><?=$e['name']?></span>
                        <span class="gb-name-b">signed:</span>
                    </div>
                    <div class="gb-comment"><?=$e['comment']?></div>
                </div>
            </form>


        <?php }} ?></div><?php
    $i++;
    $k++;
}?>
<form method="get" target="_self" id="gb-form">
    <input type="submit" value="Go back" name="go"/>
</form>
</body>
</html>
<?php
for ($i = 1; $i < $k+1; $i++) {
    if (isset($_GET['delete'.$i])) {
        $query1 = "DELETE FROM guestbook WHERE post_id='".$i."'";
        $result1 = pg_query($db, $query1) or die("Can't find data: " . pg_last_error());
        $query2 = "UPDATE guestbook SET post_id = post_id - 1 WHERE post_id>'".$i."' ";
        $result2 = pg_query($db, $query2) or die("Can't find data: " . pg_last_error());
        break;
    }
}